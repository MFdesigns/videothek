const apiRoot = `${window.location.protocol}//${window.location.host}/api`;

// HTML Elements
/* const custListTable = document.getElementById('customer-list-table');
 */
const custTableBody = document.getElementById('customer-list-output');
const tables = document.getElementsByClassName('table');

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
const custIdInput = document.querySelector('.cust-id .info-form__input');
const custTitleInput = document.querySelector('.cust-title .info-form__input');
const custNameInput = document.querySelector('.cust-name .info-form__input');
const custSurnameInput = document.querySelector('.cust-surname .info-form__input');
const custBirthdayInput = document.querySelector('.cust-birthday .info-form__input');
const custPhoneInput = document.querySelector('.cust-phone .info-form__input');
const custStreetInput = document.querySelector('.cust-street .info-form__input');
const custStreetNumberInput = document.querySelector('.cust-street-number .info-form__input');
const custONRPInput = document.querySelector('.cust-onrp .info-form__input');
const custCityInput = document.querySelector('.cust-city .info-form__input');

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

// States
const app = {
  searchActive: false,
  info: infoState.null,
  currentCustomer: null,
};

const custTable = {
  order: 'id',
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

/**
 * Fetches customer info of selected customer URL
 *
 * @param {string} apiURL
 * @returns {object} customer data
 */
async function getCustomerInfo(apiURL) {
  const request = await fetch(apiURL, { method: 'GET' });
  const response = await request.json();
  return response;
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
  custCityInput.value = data.city;
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

  // Dirty fix
  custONRPInput.value = '4805';
  custCityInput.value = 'Frauenfeld';
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

/*
  EVENT LISTENERS
*/

// On page load event
document.addEventListener('DOMContentLoaded', async () => {
  const customers = await getCustomerList(custTable.order, custTable.direction);
  displayCustomerList(customers);
});

// Handles all table events
for (let i = 0; i < tables.length; i += 1) {
  tables[i].addEventListener('click', (event) => {
    const { target } = event;

    // Check if event occured on table header or table data element
    if (target.closest('th')) {
      const th = target.closest('th'); // Clicked table header
      setTableOrder(tables[i], th, custTable);
      getCustomerList(custTable.order, custTable.direction)
        .then((customers) => {
          displayCustomerList(customers);
        });

      // If event has occured on a table row, display selected
      // customer informations
    } else if (target.closest('tr')) {
      const tr = target.closest('tr'); // Clicked table row
      if (app.info === infoState.null || app.info === infoState.show) {
        setInfoState(infoState.show);
        getCustomerInfo(tr.dataset.href).then((customer) => {
          app.currentCustomer = customer;
          displayCustomerInfo(customer);
        });
      }
    }
  });
}

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
