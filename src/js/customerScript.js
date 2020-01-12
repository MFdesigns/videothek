/* eslint-disable no-param-reassign */

const apiRoot = `${window.location.protocol}//${window.location.host}/api`;

// HTML Elements
const custSearchInput = document.getElementById('search__input');
const custListItem = document.getElementById('cust-list-item');

// Customer info form
const custInfoContextAction = document.getElementById('info-context-action');
const custInfoForm = document.getElementById('cust-info-form');

// Customer info editable input fields
const custInfoEditFields = document.getElementsByClassName('cust-info-item-editable');

// Fake enums

const MsgBoxTypes = {
  deleteCustomer: 0,
  deleteLending: 1,
};

const InfoStates = {
  null: 0,
  show: 1,
  edit: 2,
  new: 3,
};

const LendingsStates = {
  null: 0,
  show: 1,
  selected: 2,
  edit: 3,
  new: 4,
};

// Global App states and data
const App = {
  searchActive: false,
  info: InfoStates.null,
  lendings: InfoStates.null,
  selectedCustomer: null,
  selectedLending: null,
  custTable: {
    order: 'id',
    direction: 'asc',
  },
  lendingsTable: {
    order: 'vidId',
    direction: 'asc',
  },
  msgBox: null,
};

/**
 * Fetches all customers
 *
 * @param {string} orderBy
 * @param {string} orderDirection
 * @returns {object} Customer data
 */
async function getCustomerList() {
  const apiURL = new URL(`${apiRoot}/customers`);

  // Check if search is active, if thats the case use the search API URL.
  if (App.searchActive) {
    apiURL.href += '/search';
    apiURL.searchParams.set('keyword', custSearchInput.value);
  }

  apiURL.searchParams.set('order', App.custTable.order);
  apiURL.searchParams.set('direction', App.custTable.direction);

  const request = await fetch(apiURL, { method: 'GET' });
  const customers = await request.json();

  return customers;
}

/**
 * Displays table from customer data
 */
async function displayCustomerList() {
  // Fetch customers
  const customers = await getCustomerList();

  // Create document fragment to append customer table
  const fragment = document.createDocumentFragment();

  // If customers is empty display message
  if (customers.length < 1) {
    const noResultRow = document.getElementById('cust-list-search-no-result');
    fragment.appendChild(noResultRow.content.cloneNode(true));
  }

  // Loop through every customer and append table row to fragment
  for (let i = 0; i < customers.length; i += 1) {
    const customer = customers[i];

    const listItem = custListItem.content.cloneNode(true);
    const tr = listItem.querySelector('tr');
    const tds = listItem.querySelectorAll('td');

    tr.dataset.href = customer.href;
    tr.dataset.id = customer.id;
    tds[0].textContent = customer.id;
    tds[1].textContent = customer.name;
    tds[2].textContent = customer.surname;

    fragment.appendChild(listItem);
  }

  // Clear table body from previous data and
  // append new data to body.
  const custTableBody = document.getElementById('customer-list-output');
  custTableBody.innerHTML = '';
  custTableBody.appendChild(fragment);
}

/**
 * Sets visual and global data order state for the selected table
 *
 * @param {HTMLTableElement} table target table
 * @param {HTMLTableHeaderCellElement} th target table header
 * @param {*|null} stateData global object to track order state
 */
function setTableOrder(table, th, stateData) {
  // CSS classes used to display order direction
  const ascClass = 'table__order--asc';
  const descClass = 'table__order--desc';

  // Loop through all current table heads
  const tableHeads = table.getElementsByClassName('table__order');
  for (let i = 0; i < tableHeads.length; i += 1) {
    // Check if current table head is the event target
    // if thats the case switch order direction or set order
    // to default ASCENDING
    if (tableHeads[i] === th) {
      if (th.classList.contains(ascClass)) {
        th.classList.toggle(ascClass, false);
        th.classList.toggle(descClass, true);
      } else {
        th.classList.toggle(ascClass, true);
        th.classList.toggle(descClass, false);
      }
    } else {
      // If table head is not event target remove order css class
      tableHeads[i].classList.toggle(ascClass, false);
      tableHeads[i].classList.toggle(descClass, false);
    }
  }

  // If stateData param is set, set the global data to
  // new order and order direction. This is needed to keep
  // track of the current order state
  if (stateData) {
    const { order } = th.dataset;
    const direction = th.classList.contains(descClass) ? 'desc' : 'asc';

    // Update global state object
    stateData.order = order;
    stateData.direction = direction;
  }
}

async function getPlacesByPLZ(plz) {
  const request = await fetch(`${apiRoot}/places/plz/${plz}`, { method: 'GET' });
  const response = await request.json();
  return response;
}

/**
 * Fetches customer info of selected customer URL
 *
 * @param {string} apiURL
 * @returns {object} customer data
 */
async function getCustomerInfo(apiURL) {
  // Get general info
  const customerRequest = await fetch(apiURL, { method: 'GET' });
  const customer = await customerRequest.json();
  // Get place info
  const places = await getPlacesByPLZ(customer.plz);
  customer.places = places;
  return customer;
}

/**
 * Displays customer info
 *
 * @param {object} data customer data
 */
function displayCustomerInfo(data) {
  // Update HTML Form data
  custInfoForm.dataset.href = data.href;
  document.getElementById('cust-id-input').value = data.id;
  document.getElementById('cust-title-input').value = data.title;
  document.getElementById('cust-name-input').value = data.name;
  document.getElementById('cust-surname-input').value = data.surname;
  document.getElementById('cust-birthday-input').value = data.birthday;
  document.getElementById('cust-phone-input').value = data.phone;
  document.getElementById('cust-street-input').value = data.street;
  document.getElementById('cust-street-number-input').value = data.streetNumber;
  document.getElementById('cust-onrp-input').value = data.onrp;
  document.getElementById('cust-plz-input').value = data.plz;
  const custCityInput = document.getElementById('cust-city-input');

  // City select
  custCityInput.innerHTML = '';

  data.places.forEach((place) => {
    const option = document.createElement('option');
    option.value = place.onrp;
    option.textContent = place.city;

    if (place.onrp === data.onrp) {
      option.selected = true;
    }

    custCityInput.appendChild(option);
  });
}

/**
 * Disables or enables customer info action buttons
 *
 * @param {bool} state edit, delete customer
 * @param {bool} newBtnState create customer
 */
function enableInfoActionButtons(state, newBtnState) {
  document.querySelectorAll('#info-action > .action-button').forEach((btn) => {
    if (btn.id === 'cust-add-btn') {
      btn.toggleAttribute('disabled', !newBtnState);
    } else {
      btn.toggleAttribute('disabled', !state);
    }
  });
}

/**
 * Sets state to HTML Inputs and global data
 *
 * @param {bool} state
 */
function enableCustomerEditMode(state) {
  // Enable or disables all editable inputs in the customer info form
  for (let i = 0; i < custInfoEditFields.length; i += 1) {
    if (state) {
      if (custInfoEditFields[i].nodeName === 'SELECT') {
        custInfoEditFields[i].toggleAttribute('disabled', false);
      } else {
        custInfoEditFields[i].toggleAttribute('readonly', false);
      }
    } else if (custInfoEditFields[i].nodeName === 'SELECT') {
      custInfoEditFields[i].toggleAttribute('disabled', true);
    } else {
      custInfoEditFields[i].toggleAttribute('readonly', true);
    }
  }
  // Enable/Disable search button
  if (state) {
    document.getElementById('plz-search-btn').style.visibility = 'visible';
  } else {
    document.getElementById('plz-search-btn').style.visibility = 'hidden';
  }
}

/**
 * Sets state of customer info context buttons
 * @param {bool} state
 */
function enableInfoContextButtons(state) {
  if (state) {
    custInfoContextAction.style.visibility = 'visible';
  } else {
    custInfoContextAction.style.visibility = 'hidden';
  }
}

/**
 * Converts FormData to JSON (API Helper function)
 *
 * @param {FormData} formData
 */
function convertInfoFormToJson(formData) {
  const data = Array.from(formData.entries());

  const json = {};
  for (let i = 0; i < data.length; i += 1) {
    const key = data[i][0];
    const value = data[i][1];
    json[key] = value;
  }

  return json;
}

/**
 * Updates customer data
 *
 * @param {object} jsonData
 */
async function updateCustomer(jsonData) {
  const apiURL = custInfoForm.dataset.href;

  await fetch(apiURL, {
    method: 'PUT',
    headers: {
      'Content-Type': 'Application/json',
    },
    body: JSON.stringify(jsonData),
  });
}

/**
 * Deletes customer
 *
 * @param {string} apiURL
 */
async function deleteCustomer(apiURL) {
  await fetch(apiURL, { method: 'DELETE' });
}

/**
 * Adds a new customer
 *
 * @param {object} data
 */
async function addCustomer(data) {
  const apiURL = new URL(`${apiRoot}/customers`);

  const request = await fetch(apiURL, {
    method: 'POST',
    body: JSON.stringify(data),
  });

  const response = await request.json();
  return response;
}

/**
 * Displays the message box of provided type
 *
 * @param {bool} state
 */
function displayMsgBox(type) {
  // Set global msg box type
  App.msgBox = type;

  // Disable page scrolling
  document.body.classList.toggle('body-no-scroll', true);

  const msgBox = document.getElementsByClassName('msg-box')[0];
  const title = document.getElementsByClassName('msg-box__title')[0];
  const content = document.getElementsByClassName('msg-box__content')[0];

  // Display msg box
  msgBox.style.display = 'grid';

  // Possible msg box titles
  const titles = {
    user: 'Wollen Sie den Kunden wirklich löschen?',
    lending: 'Wollen Sie die Ausleihe wirklich löschen?',
  };

  switch (type) {
    case MsgBoxTypes.deleteCustomer:
      // Display customer id, name and surname to be deleted
      title.textContent = titles.user;
      content.textContent = `#${App.selectedCustomer.id} ${App.selectedCustomer.name} ${App.selectedCustomer.surname}`;
      break;

    case MsgBoxTypes.deleteLending:
      // Display video id and title to be deleted
      title.textContent = titles.lending;
      content.textContent = `#${App.selectedLending.vidId} ${App.selectedLending.title}`;
      break;

    default:
      break;
  }
}

/**
 * Clears customer info form and displays empty values
 */
function clearCustomerForm() {
  const custCityInput = document.getElementById('cust-city-input');
  // Clear all inputs
  custInfoForm.dataset.href = '';
  document.querySelectorAll('.info-form__input').forEach((input) => {
    input.value = '';
  });
  custCityInput.innerHTML = '';
}

/**
 * Sets the visual state of customer info
 *
 * @param {number} state
 */
function setInfoState(state) {
  switch (state) {
    case InfoStates.null:
      enableInfoActionButtons(false, true);
      enableCustomerEditMode(false);
      enableInfoContextButtons(false);
      clearCustomerForm();
      break;

    case InfoStates.show:
      enableCustomerEditMode(false);
      enableInfoContextButtons(false);
      enableInfoActionButtons(true, true);
      break;

    case InfoStates.new:
      clearCustomerForm();
      enableCustomerEditMode(true);
      enableInfoContextButtons(true);
      enableInfoActionButtons(false, false);
      // Hide all lendings
      document.getElementById('customer-lendings-output').style.visibility = 'hidden';
      break;

    case InfoStates.edit:
      enableCustomerEditMode(true);
      enableInfoContextButtons(true);
      enableInfoActionButtons(false, false);
      break;

    default:
      break;
  }

  App.info = state;
}

/**
 * Adds new lending to selectd customer
 *
 * @param {object} data
 */
async function addLending(data) {
  const request = await fetch(`${apiRoot}/lendings`, {
    method: 'POST',
    body: JSON.stringify(data),
  });
  const response = await request.json();
  return response;
}

/**
 * Fetches all lendings of customer
 *
 * @param {number} custId
 * @returns {object} lendings
 */
async function getLendings(custId) {
  const apiURL = new URL(`${apiRoot}/lendings/customer/${custId}`);
  apiURL.searchParams.set('order', App.lendingsTable.order);
  apiURL.searchParams.set('direction', App.lendingsTable.direction);

  const request = await fetch(apiURL, { method: 'GET' });
  const lendings = await request.json();
  return lendings;
}

/**
 * Fetches and displays provided lendings data
 */
async function displayLendings() {
  const lendings = await getLendings(App.selectedCustomer.id);

  const tbody = document.getElementById('customer-lendings-output');
  const template = document.getElementById('cust-lendings-item');

  if (lendings.length <= 0) {
    tbody.innerHTML = '';
  } else {
    const fragment = document.createDocumentFragment();

    for (let i = 0; i < lendings.length; i += 1) {
      const temp = template.content.cloneNode(true);
      const tr = temp.querySelector('tr');
      const tds = temp.querySelectorAll('td');

      let { from } = lendings[i];
      let { until } = lendings[i];

      // Convert databse date format to local ch format
      if (from !== null) {
        const fromDate = new Date(from);
        from = new Intl.DateTimeFormat('de-ch', {
          dateStyle: 'medium',
        }).format(fromDate);
      }
      if (until !== null) {
        const untilDate = new Date(until);
        until = new Intl.DateTimeFormat('de-ch', {
          dateStyle: 'medium',
        }).format(untilDate);
      }

      tr.dataset.id = lendings[i].lendId;
      tr.dataset.vidId = lendings[i].vidId;
      tr.dataset.from = lendings[i].from;
      tr.dataset.until = lendings[i].until;

      tds[0].textContent = lendings[i].vidId;
      tds[1].textContent = lendings[i].title;
      tds[2].textContent = from;
      tds[3].textContent = until;

      fragment.appendChild(temp);
    }

    tbody.innerHTML = '';
    tbody.appendChild(fragment);
  }
}

/**
 * Updates slected lending
 *
 * @param {number} id
 * @param {object} data
 */
async function updateLending(id, data) {
  const apiURL = `${apiRoot}/lendings/${id}`;
  await fetch(apiURL, {
    method: 'PUT',
    body: JSON.stringify(data),
  });
}

/**
 * Delets selected lending
 *
 * @param {number} id
 */
async function deleteLending(id) {
  const apiURL = `${apiRoot}/lendings/${id}`;
  await fetch(apiURL, { method: 'DELETE' });
}

/**
 * Sets the visual state of lendings section
 *
 * @param {number} state
 */
function setLendingsState(state) {
  const editOverlay = document.getElementsByClassName('customer-lending-overlay')[0];
  const editBtn = document.getElementById('lend-edit-btn');
  const deleteBtn = document.getElementById('lend-delete-btn');
  const addBtn = document.getElementById('lend-add-btn');

  App.lendings = state;

  switch (state) {
    case LendingsStates.null:
      editOverlay.style.display = 'none';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', true);
      break;

    case LendingsStates.show:
      editOverlay.style.display = 'none';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', false);
      break;

    case LendingsStates.selected:
      editOverlay.style.display = 'none';
      editBtn.toggleAttribute('disabled', false);
      deleteBtn.toggleAttribute('disabled', false);
      addBtn.toggleAttribute('disabled', false);
      break;

    case LendingsStates.edit:
      editOverlay.style.display = 'block';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', true);
      break;

    case LendingsStates.new:
      editOverlay.style.display = 'block';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', true);
      break;

    default:
      break;
  }
}

/**
 * Handles all button click events
 *
 * @param {Event} event
 */
function msgBoxEventHandler(event) {
  const btn = event.target.closest('.msg-box__button');
  if (btn && btn.id === 'msg-yes-btn') {
    switch (App.msgBox) {
      case MsgBoxTypes.deleteCustomer:
        deleteCustomer(custInfoForm.dataset.href).then(() => {
          setInfoState(InfoStates.null);
          setLendingsState(LendingsStates.null);
          displayCustomerList();
        });
        App.selectedCustomer = null;
        // Clear all lendings
        document.getElementById('customer-lendings-output').innerHTML = '';
        break;

      case MsgBoxTypes.deleteLending:
        deleteLending(App.selectedLending.lendId).then(() => {
          displayLendings();
        });
        break;

      default:
        break;
    }

    // Hide message box
    document.getElementsByClassName('msg-box')[0].style.display = 'none';
    // Enable page scrolling
    document.body.classList.toggle('body-no-scroll', false);
  } else if (btn) {
    // Hide message box
    document.getElementsByClassName('msg-box')[0].style.display = 'none';
    // Enable page scrolling
    document.body.classList.toggle('body-no-scroll', false);
  }
}

/**
 * Adds visual indicator to selected row
 *
 * @param {HTMLTableElement} table
 * @param {HTMLTableRowElement} selectedTr
 */
function selectRow(table, selectedTr) {
  table.querySelectorAll('tr').forEach((tr) => {
    if (tr === selectedTr) {
      tr.classList.toggle('selected-row', true);
    } else {
      tr.classList.toggle('selected-row', false);
    }
  });
}

/*
  EVENT LISTENERS
*/

// On page load event
document.addEventListener('DOMContentLoaded', () => {
  displayCustomerList();
});

// Handles all table events
document.querySelectorAll('.table').forEach((table) => {
  table.addEventListener('click', (event) => {
    const { target } = event;
    const th = target.closest('th'); // Clicked table header
    const tr = target.closest('tr'); // Clicked table row

    // Check if event occured on table header or table data element
    if (th) {
      if (table.id === 'customer-list-table') {
        setTableOrder(table, th, App.custTable);
        displayCustomerList();
      } else {
        setTableOrder(table, th, App.lendingsTable);
        if (App.selectedCustomer) {
          displayLendings();
        }
      }

      // If event has occured on a table row, display selected
      // customer informations
    } else if (tr) {
      if (table.id === 'customer-list-table') {
        if (App.info === InfoStates.null || App.info === InfoStates.show) {
          setInfoState(InfoStates.show);
          getCustomerInfo(tr.dataset.href).then((customer) => {
            App.selectedCustomer = customer;
            displayCustomerInfo(customer);

            displayLendings().then(() => {
              setLendingsState(LendingsStates.show);
            });
          });
        }
      } else if (App.info === InfoStates.show) {
        const tds = tr.querySelectorAll('td');
        App.selectedLending = {
          lendId: tr.dataset.id,
          vidId: tr.dataset.vidId,
          from: tr.dataset.from,
          title: tds[1].textContent,
          until: tr.dataset.until,
        };
        selectRow(table, tr);
        setLendingsState(LendingsStates.selected);
      }
    }
  });
});

// Customer search events

// Perform search event handler
document.getElementById('cust-search').addEventListener('submit', (event) => {
  event.preventDefault();
  App.searchActive = true;
  displayCustomerList();
});

// Clear search event handler
document.getElementById('cust-search-clear').addEventListener('click', () => {
  custSearchInput.value = '';
  App.searchActive = false;
  displayCustomerList();
});

// Handles all buttons in customer info (New, Edit, Delete Customer)
document.getElementById('info-action').addEventListener('click', (event) => {
  const button = event.target.closest('.action-button');

  switch (button.id) {
    case 'cust-edit-btn':
      setInfoState(InfoStates.edit);
      setLendingsState(LendingsStates.null);
      break;

    case 'cust-delete-btn':
      displayMsgBox(MsgBoxTypes.deleteCustomer);
      break;

    case 'cust-add-btn':
      setInfoState(InfoStates.new);
      setLendingsState(LendingsStates.null);
      break;

    default:
      break;
  }
});

document.getElementById('lend-action').addEventListener('click', (event) => {
  const targetId = event.target.closest('.action-button').id;

  const idInput = document.getElementById('lend-vidId');
  const fromInput = document.getElementById('lend-from');
  const untilInput = document.getElementById('lend-until');

  switch (targetId) {
    case 'lend-edit-btn':
      setLendingsState(LendingsStates.edit);
      idInput.value = App.selectedLending.vidId;
      fromInput.value = App.selectedLending.from;
      untilInput.value = App.selectedLending.until;
      break;

    case 'lend-delete-btn':
      displayMsgBox(MsgBoxTypes.deleteLending);
      break;

    case 'lend-add-btn':
      setLendingsState(LendingsStates.new);
      document.getElementById('customer-lendings-output').style.visibility = 'visible';
      break;

    default:
      break;
  }
});

// Handles all customer info context buttons (Save, Cancel)
custInfoContextAction.addEventListener('click', (event) => {
  event.preventDefault();
  const button = event.target.closest('.info-form__action-button');

  switch (button.id) {
    case 'info-save-btn':

      if (custInfoForm.checkValidity()) {
        const form = new FormData(custInfoForm);
        const formJSON = convertInfoFormToJson(form);

        switch (App.info) {
          case InfoStates.edit:
            updateCustomer(formJSON).then(() => {
              getCustomerInfo(custInfoForm.dataset.href).then((customer) => {
                App.selectedCustomer = customer;
                setInfoState(InfoStates.show);
                setLendingsState(LendingsStates.show);
                displayCustomerInfo(customer);
                // Update customer list
                displayCustomerList();
              });
            });
            break;

          case InfoStates.new:
            addCustomer(formJSON).then((customerHref) => {
              setInfoState(InfoStates.show);

              getCustomerInfo(customerHref.href).then((customer) => {
                App.selectedCustomer = customer;
                displayCustomerInfo(customer);
                // Update customer list and lendings
                setLendingsState(LendingsStates.show);
                displayCustomerList();
                displayLendings();
                // Show all lendings
                document.getElementById('customer-lendings-output').style.visibility = 'visible';
              });

            });

            break;

          default:
            break;
        }
      } else {
        custInfoForm.reportValidity();
      }
      break;

    case 'info-cancel-btn':
      if (App.info === InfoStates.new && App.selectedCustomer === null) {
        setInfoState(InfoStates.null);
      } else if (App.info === InfoStates.new && App.selectedCustomer !== null) {
        setInfoState(InfoStates.show);
      } else {
        setInfoState(InfoStates.show);
        displayCustomerInfo(App.selectedCustomer);
      }
      setLendingsState(LendingsStates.show);
      // Show all lendings
      document.getElementById('customer-lendings-output').style.visibility = 'visible';
      break;

    default:
      break;
  }
});

document.getElementById('lend-save-btn').addEventListener('click', (event) => {
  event.preventDefault();

  const form = document.getElementById('cust-lending-form');

  if (form.checkValidity()) {
    const formData = new FormData(form);
    formData.append('custId', App.selectedCustomer.id);

    switch (App.lendings) {
      case LendingsStates.new:
        addLending(convertInfoFormToJson(formData)).then(() => {
          displayLendings();
        });
        break;

      case LendingsStates.edit:
        updateLending(App.selectedLending.lendId, convertInfoFormToJson(formData)).then(() => {
          displayLendings();
          setInfoState(InfoStates.show);
        });
        break;

      default:
        break;
    }
    setLendingsState(LendingsStates.show);
  } else {
    form.reportValidity();
  }
});

document.getElementById('lend-cancel-btn').addEventListener('click', () => {
  setLendingsState(LendingsStates.show);
});

document.getElementById('plz-search-btn').addEventListener('click', async () => {
  const custCityInput = document.getElementById('cust-city-input');
  const custPLZInput = document.getElementById('cust-plz-input');
  const custONRPInput = document.getElementById('cust-onrp-input');

  getPlacesByPLZ(custPLZInput.value).then((places) => {
    custCityInput.innerHTML = '';

    if (places.length <= 0) {
      const defaultOption = document.createElement('option');
      defaultOption.value = '';
      defaultOption.selected = true;
      defaultOption.textContent = 'Keine Stadt gefunden';
      custCityInput.appendChild(defaultOption);
    } else {
      custONRPInput.value = places[0].onrp;
    }

    places.forEach((place) => {
      const option = document.createElement('option');
      option.value = place.onrp;
      option.textContent = place.city;

      custCityInput.appendChild(option);
    });
  });
});

document.getElementById('cust-city-input').addEventListener('change', (event) => {
  const custONRPInput = document.getElementById('cust-onrp-input');
  const onrp = event.target.value;
  if (onrp !== '') {
    custONRPInput.value = onrp;
  }
});

document.getElementsByClassName('msg-box__buttons')[0].addEventListener('click', (event) => {
  msgBoxEventHandler(event);
});
