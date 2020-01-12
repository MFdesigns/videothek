const apiRoot = `${window.location.protocol}//${window.location.host}/api`;

// HTML Elements
/* const custListTable = document.getElementById('customer-list-table');
 */
const custTableBody = document.getElementById('customer-list-output');

const custSearch = document.getElementById('cust-search');
const custSearchInput = document.getElementById('search__input');
const custSearchClear = document.getElementById('cust-search-clear');

// Templates
const custListNegativeSearch = document.getElementById('cust-list-search-no-result');
const custListItem = document.getElementById('cust-list-item');

// Customer info form
const custInfoAction = document.getElementById('info-action');
const custInfoContextAction = document.getElementById('info-context-action');
const custInfoForm = document.getElementById('cust-info-form');

// Customer infos input fields
const custIdInput = document.getElementById('cust-id-input');
const custTitleInput = document.getElementById('cust-title-input');
const custNameInput = document.getElementById('cust-name-input');
const custSurnameInput = document.getElementById('cust-surname-input');
const custBirthdayInput = document.getElementById('cust-birthday-input');
const custPhoneInput = document.getElementById('cust-phone-input');
const custStreetInput = document.getElementById('cust-street-input');
const custStreetNumberInput = document.getElementById('cust-street-number-input');
const custONRPInput = document.getElementById('cust-onrp-input');
const custPLZInput = document.getElementById('cust-plz-input');
const custCityInput = document.getElementById('cust-city-input');

// Customer info editable input fields
const custInfoEditFields = document.getElementsByClassName('cust-info-item-editable');

// Message boxes
const msgDeleteUser = document.getElementById('msg-delete-user');

const infoState = {
  null: 0,
  show: 1,
  edit: 2,
  new: 3,
};

const lendingsState = {
  null: 0,
  show: 1,
  selected: 2,
  edit: 3,
  new: 4,
};

// States
const app = {
  searchActive: false,
  info: infoState.null,
  lendings: infoState.null,
  currentCustomer: null,
  selectedLending: null,
};

const custTable = {
  order: 'id',
  direction: 'asc',
};

const lendingsTable = {
  order: 'vidId',
  direction: 'asc',
};

/**
 * Displays table from customer data
 *
 * @param {*} data API Data
 */
function displayCustomerList(data) {
  // Create document fragment to append customer table
  const fragment = document.createDocumentFragment();

  // If data is empty display message
  if (data.length < 1) {
    fragment.appendChild(custListNegativeSearch.content.cloneNode(true));
  }

  // Loop through every customer and append table row to fragment
  for (let i = 0; i < data.length; i += 1) {
    const customer = data[i];

    const listItem = custListItem.content.cloneNode(true);
    const tr = listItem.querySelector('tr');
    const tds = listItem.querySelectorAll('td');

    tr.dataset.href = customer.href;
    tr.dataset.id = customer.id;
    tds[0].textContent = customer.id;
    tds[1].textContent = customer.name;
    tds[2].textContent = customer.surname;

    fragment.appendChild(tr);
  }

  // Clear table body from previous data and
  // append new data to body.
  custTableBody.innerHTML = '';
  custTableBody.appendChild(fragment);
}

/**
 * Fetches all customers
 *
 * @param {string} orderBy
 * @param {string} orderDirection
 * @returns {*} Customer data
 */
async function getCustomerList(orderBy, orderDirection) {
  const apiURL = new URL(`${apiRoot}/customers`);

  // Check if search is active, if thats the case use the search API URL.
  if (app.searchActive) {
    apiURL.href += '/search';
    apiURL.searchParams.set('keyword', custSearchInput.value);
  }

  apiURL.searchParams.set('order', orderBy);
  apiURL.searchParams.set('direction', orderDirection);

  const request = await fetch(apiURL, { method: 'GET' });
  const customers = await request.json();

  return customers;
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
  // Set customer info API href
  custInfoForm.dataset.href = data.href;

  // Update HTML Form data
  custIdInput.value = data.id;
  custTitleInput.value = data.title;
  custNameInput.value = data.name;
  custSurnameInput.value = data.surname;
  custBirthdayInput.value = data.birthday;
  custPhoneInput.value = data.phone;
  custStreetInput.value = data.street;
  custStreetNumberInput.value = data.streetNumber;
  custONRPInput.value = data.onrp;
  custPLZInput.value = data.plz;

  custCityInput.innerHTML = '';
  // City select
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
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(jsonData),
  });
}

/**
 * Displays the customer delete action message box
 *
 * @param {bool} state
 */
function displayCustDeleteMsg(state) {
  if (state) {
    // Display customer id, name and surname to be deleted
    const p = document.getElementById('msg-delete-user-info');
    const formData = new FormData(custInfoForm);
    const text = `#${formData.get('id')} ${formData.get('name')} ${formData.get('surname')}`;
    p.textContent = text;

    msgDeleteUser.style.display = 'grid';
  } else {
    msgDeleteUser.style.display = 'none';
  }
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
 * Clears customer info form and displays empty values
 */
function clearCustomerForm() {
  // Clear all inputs
  custInfoForm.dataset.href = '';
  custIdInput.value = '';
  custTitleInput.value = '';
  custNameInput.value = '';
  custSurnameInput.value = '';
  custBirthdayInput.value = '2000-01-01';
  custPhoneInput.value = '';
  custStreetInput.value = '';
  custStreetNumberInput.value = '';
  custONRPInput.value = '';
  custCityInput.innerHTML = '';
}

function setInfoState(state) {
  switch (state) {
    case infoState.null:
      enableInfoActionButtons(false, true);
      enableCustomerEditMode(false);
      enableInfoContextButtons(false);
      clearCustomerForm();
      break;

    case infoState.show:
      enableCustomerEditMode(false);
      enableInfoContextButtons(false);
      enableInfoActionButtons(true, true);
      break;

    case infoState.new:
      clearCustomerForm();
      enableCustomerEditMode(true);
      enableInfoContextButtons(true);
      enableInfoActionButtons(false, false);
      break;

    case infoState.edit:
      enableCustomerEditMode(true);
      enableInfoContextButtons(true);
      enableInfoActionButtons(false, false);
      break;

    default:
      break;
  }

  app.info = state;
}

function setLendingsState(state) {

  const editOverlay = document.getElementsByClassName('customer-lending-overlay')[0];
  const editBtn = document.getElementById('lend-edit-btn');
  const deleteBtn = document.getElementById('lend-delete-btn');
  const addBtn = document.getElementById('lend-add-btn');

  app.lendings = state;

  switch (state) {
    case lendingsState.null:
      editOverlay.style.display = 'none';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', true);
      break;

    case lendingsState.show:
      editOverlay.style.display = 'none';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', false);
      break;

    case lendingsState.selected:
      editOverlay.style.display = 'none';
      editBtn.toggleAttribute('disabled', false);
      deleteBtn.toggleAttribute('disabled', false);
      addBtn.toggleAttribute('disabled', false);
      break;

    case lendingsState.edit:
      editOverlay.style.display = 'block';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', true);
      break;

    case lendingsState.new:
      editOverlay.style.display = 'block';
      editBtn.toggleAttribute('disabled', true);
      deleteBtn.toggleAttribute('disabled', true);
      addBtn.toggleAttribute('disabled', true);
      break;

    default:
      break;
  }
}

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
async function getLendings(custId, orderBy, orderDirection) {
  const apiURL = new URL(`${apiRoot}/lendings/customer/${custId}`);
  apiURL.searchParams.set('order', orderBy);
  apiURL.searchParams.set('direction', orderDirection);

  const request = await fetch(apiURL, { method: 'GET' });
  const lendings = await request.json();
  return lendings;
}

async function updateLending(id, data) {
  const apiURL = `${apiRoot}/lendings/${id}`;
  await fetch(apiURL, {
    method: 'PUT',
    body: JSON.stringify(data),
  });
}

/**
 * Displays provided lendings data
 *
 * @param {object} data
 */
function displayLendings(data) {
  const tbody = document.getElementById('customer-lendings-output');
  const template = document.getElementById('cust-lendings-item');

  if (data.length <= 0) {
    tbody.innerHTML = '';
  } else {
    const fragment = document.createDocumentFragment();

    for (let i = 0; i < data.length; i += 1) {
      const temp = template.content.cloneNode(true);
      const tr = temp.querySelector('tr');
      const tds = temp.querySelectorAll('td');

      tr.dataset.id = data[i].lendId;

      tds[0].textContent = data[i].vidId;
      tds[1].textContent = data[i].title;
      tds[2].textContent = data[i].from;
      tds[3].textContent = data[i].until;

      fragment.appendChild(temp);
    }

    tbody.innerHTML = '';
    tbody.appendChild(fragment);
  }
}

function selectRow(table, selectedTr) {
  table.querySelectorAll('tr').forEach((tr) => {
    if (tr === selectedTr) {
      tr.classList.toggle('selected-row', true);
    } else {
      tr.classList.toggle('selected-row', false);
    }
  });
}

async function deleteLending(id) {
  const apiURL = `${apiRoot}/lendings/${id}`;
  await fetch(apiURL, { method: 'DELETE' });
}

/*
  EVENT LISTENERS
*/

// On page load event
document.addEventListener('DOMContentLoaded', async () => {
  const customers = await getCustomerList(custTable.order, custTable.direction);
  displayCustomerList(customers);
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
        setTableOrder(table, th, custTable);
        getCustomerList(custTable.order, custTable.direction)
          .then((customers) => {
            displayCustomerList(customers);
          });
      } else {
        setTableOrder(table, th, lendingsTable);
        if (app.currentCustomer) {
          getLendings(app.currentCustomer.id, lendingsTable.order, lendingsTable.direction)
            .then((lendings) => {
              displayLendings(lendings);
            });
        }
      }

      // If event has occured on a table row, display selected
      // customer informations
    } else if (tr) {
      if (table.id === 'customer-list-table') {
        if (app.info === infoState.null || app.info === infoState.show) {
          setInfoState(infoState.show);
          getCustomerInfo(tr.dataset.href).then((customer) => {
            app.currentCustomer = customer;
            displayCustomerInfo(customer);
          });

          getLendings(tr.dataset.id, lendingsTable.order, lendingsTable.direction)
            .then((lendings) => {
              displayLendings(lendings);
              setLendingsState(lendingsState.show);
            });
        }
      } else {
        const tds = tr.querySelectorAll('td');
        app.selectedLending = {
          lendId: tr.dataset.id,
          vidId: tds[0].textContent,
          from: tds[2].textContent,
          until: tds[3].textContent,
        };
        selectRow(table, tr);
        setLendingsState(lendingsState.selected);
      }
    }
  });
});

// Customer search events

// Perform search event handler
custSearch.addEventListener('submit', (event) => {
  event.preventDefault();
  app.searchActive = true;
  getCustomerList(custTable.order, custTable.direction).then((customers) => {
    displayCustomerList(customers);
  });
});

// Clear search event handler
custSearchClear.addEventListener('click', () => {
  custSearchInput.value = '';
  app.searchActive = false;
  getCustomerList(custTable.order, custTable.direction).then((customers) => {
    displayCustomerList(customers);
  });
});

// Handles all buttons in customer info (New, Edit, Delete Customer)
custInfoAction.addEventListener('click', (event) => {
  const targetId = event.target.id;

  switch (targetId) {
    case 'cust-edit-btn':
      setInfoState(infoState.edit);
      break;

    case 'cust-delete-btn':
      displayCustDeleteMsg(true);
      break;

    case 'cust-add-btn':
      setInfoState(infoState.new);
      break;

    default:
      break;
  }
});

document.getElementById('lend-action').addEventListener('click', (event) => {
  const targetId = event.target.id;

  const idInput = document.getElementById('lend-vidId');
  const fromInput = document.getElementById('lend-from');
  const untilInput = document.getElementById('lend-until');

  switch (targetId) {
    case 'lend-edit-btn':
      setLendingsState(lendingsState.edit);
      idInput.value = app.selectedLending.lendId;
      fromInput.value = app.selectedLending.from;
      untilInput.value = app.selectedLending.until;
      break;

    case 'lend-delete-btn':
      document.getElementById('msg-delete-lending').style.display = 'grid';
      break;

    case 'lend-add-btn':
      setLendingsState(lendingsState.new);
      break;

    default:
      break;
  }
});

// Handles all customer info context buttons (Save, Cancel)
custInfoContextAction.addEventListener('click', (event) => {
  event.preventDefault();
  const targetId = event.target.id;

  switch (targetId) {
    case 'info-save-btn':

      if (custInfoForm.checkValidity()) {
        const form = new FormData(custInfoForm);
        const formJSON = convertInfoFormToJson(form);

        switch (app.info) {
          case infoState.edit:
            updateCustomer(formJSON).then(() => {
              getCustomerInfo(custInfoForm.dataset.href).then((customer) => {
                setInfoState(infoState.show);
                displayCustomerInfo(customer);
                // Update customer list
                getCustomerList(custTable.order, custTable.direction).then((customers) => {
                  displayCustomerList(customers);
                });
              });
            });
            break;

          case infoState.new:
            addCustomer(formJSON).then((customerHref) => {
              setInfoState(infoState.show);

              getCustomerInfo(customerHref.href).then((customer) => {
                displayCustomerInfo(customer);
              });

              // Update customer list
              getCustomerList(custTable.order, custTable.direction).then((customers) => {
                displayCustomerList(customers);
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
      if (app.info === infoState.new) {
        setInfoState(infoState.null);
      } else {
        setInfoState(infoState.show);
        displayCustomerInfo(app.currentCustomer);
      }
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
    formData.append('custId', app.currentCustomer.id);

    switch (app.lendings) {
      case lendingsState.new:
        addLending(convertInfoFormToJson(formData)).then(() => {
          getLendings(app.currentCustomer.id, lendingsTable.order, lendingsTable.direction)
            .then((lendings) => {
              displayLendings(lendings);
            });
        });
        break;

      case lendingsState.edit:
        updateLending(app.selectedLending.lendId, convertInfoFormToJson(formData)).then(() => {
          getLendings(app.currentCustomer.id, lendingsTable.order, lendingsTable.direction)
            .then((lendings) => {
              displayLendings(lendings);
            });
          setInfoState(infoState.show);
        });
        break;

      default:
        break;
    }
    setLendingsState(lendingsState.show);
  }
});

document.getElementById('lend-cancel-btn').addEventListener('click', () => {
  setLendingsState(lendingsState.show);
});

/* Message box event handlers */

msgDeleteUser.addEventListener('click', (event) => {
  const { target } = event;
  switch (target.id) {
    case 'msg-delete-cust-yes':
      deleteCustomer(custInfoForm.dataset.href).then(() => {
        setInfoState(infoState.null);
        getCustomerList(custTable.order, custTable.direction).then((customers) => {
          displayCustomerList(customers);
        });
      });
      displayCustDeleteMsg(false);
      break;

    case 'msg-delete-cust-no':
      displayCustDeleteMsg(false);
      break;

    default:
      break;
  }
});

document.getElementById('msg-delete-lending').addEventListener('click', (event) => {
  const { target } = event;

  switch (target.id) {
    case 'msg-delete-lend-yes':
      deleteLending(app.selectedLending.lendId).then(() => {
        getLendings(app.currentCustomer.id, lendingsTable.order, lendingsTable.direction)
          .then((lendings) => {
            displayLendings(lendings);
          });
      });
      event.currentTarget.style.display = 'none';
      break;

    case 'msg-delete-lend-no':
      event.currentTarget.style.display = 'none';
      break;

    default:
      break;
  }
});

document.getElementById('plz-search-btn').addEventListener('click', async () => {
  const plzInput = custPLZInput.value;

  getPlacesByPLZ(plzInput).then((places) => {
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
  const onrp = event.target.value;
  if (onrp !== '') {
    custONRPInput.value = onrp;
  }
});
