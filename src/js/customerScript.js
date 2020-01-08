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
const custIdInput = document.getElementById('cust-id');
const custTitleInput = document.getElementById('cust-title');
const custNameInput = document.getElementById('cust-name');
const custSurnameInput = document.getElementById('cust-surname');
const custBirthdayInput = document.getElementById('cust-birthday');
const custPhoneInput = document.getElementById('cust-phone');
const custStreetInput = document.getElementById('cust-street');
const custStreetNumberInput = document.getElementById('cust-streetnumber');
const custONRPInput = document.getElementById('cust-onrp');
const custCityInput = document.getElementById('cust-city');

// Customer info editable input fields
const custInfoEditFields = document.getElementsByClassName('cust-info-item-editable');
const custInfoEditBtn = document.getElementById('cust-edit-btn');
const custInfoDeleteBtn = document.getElementById('cust-delete-btn');
const custInfoAddBtn = document.getElementById('cust-add-btn');
const custInfoCancelBtn = document.getElementById('cust-info-cancel-btn');
const custInfoSaveBtn = document.getElementById('cust-info-save-btn');

// Message boxes
const msgDeleteUser = document.getElementById('msg-delete-user');

const infoState = {
  show: 0,
  edit: 1,
  new: 2,
};

// States
const app = {
  searchActive: false,
  info: infoState.show,
  customerHref: '',
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
 * @param {bool} state
 */
function enableInfoActionButtons(state) {
  document.querySelectorAll('#info-action > .action-button').forEach((btn) => {
    btn.toggleAttribute('disabled', !state);
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
      custInfoEditFields[i].toggleAttribute('readonly', false);
    } else {
      custInfoEditFields[i].toggleAttribute('readonly', true);
      // If edit mode is disabled get current customer data and display it
      getCustomerInfo(custInfoForm.dataset.href).then((customer) => {
        displayCustomerInfo(customer);
      });
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

  const request = await fetch(apiURL, {
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
  custCityInput.value = '';
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
      getCustomerInfo(tr.dataset.href).then((customer) => {
        displayCustomerInfo(customer);
      });
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
  const currentCustHref = custInfoForm.dataset.href;

  switch (targetId) {
    case 'cust-edit-btn':
      enableInfoActionButtons(false);
      app.info = infoState.edit;
      enableCustomerEditMode(true);
      enableInfoContextButtons(true);
      break;

    case 'cust-delete-btn':
      displayCustDeleteMsg(true);
      break;

    case 'cust-add-btn':
      clearCustomerForm();
      app.info = infoState.edit;
      enableCustomerEditMode(true);
      enableInfoContextButtons(true);
      enableInfoActionButtons(false);
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
        updateCustomer(formJSON).then(() => {
          getCustomerInfo(custInfoForm.dataset.href).then((customer) => {
            displayCustomerInfo(customer);
            app.info = infoState.show;
            enableCustomerEditMode(false);
            enableInfoActionButtons(true);
            enableInfoContextButtons(false);
            // Update customer list
            getCustomerList(custTable.order, custTable.direction).then((customers) => {
              displayCustomerList(customers);
            });
          });
        });
      }
      break;

    case 'info-cancel-btn':
      enableInfoActionButtons(true);
      app.info = infoState.show;
      enableCustomerEditMode(false);
      enableInfoContextButtons(false);
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
        getCustomerList(custTable.order, custTable.direction).then((customers) => {
          displayCustomerList(customers);
        });
        clearCustomerForm();
        displayCustDeleteMsg(false);
      });
      break;

    case 'msg-delete-cust-no':
      displayCustDeleteMsg(false);
      break;

    default:
      break;
  }
});
