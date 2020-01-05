const apiRoot = `${window.location.protocol}//${window.location.host}/api`;

// HTML Elements
/* const custListTable = document.getElementById('customer-list-table');
 */
const custListOutput = document.getElementById('customer-list-output');
const tables = document.getElementsByClassName('table');

const custSearch = document.getElementById('cust-search');
const custSearchInput = document.getElementById('search__input');
const custSearchClear = document.getElementById('cust-search-clear');

// Templates
const custListNegativeSearch = document.getElementById('cust-list-search-no-result');
const custListItem = document.getElementById('cust-list-item');

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

// States
const app = {
  searchActive: false,
};

const custTable = {
  order: 'id',
  direction: 'asc',
};

function displayCustomerList(data) {
  const fragment = document.createDocumentFragment();

  if (data.length < 1) {
    fragment.appendChild(custListNegativeSearch.content.cloneNode(true));
  }

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

  custListOutput.innerHTML = '';
  custListOutput.appendChild(fragment);
}

async function getCustomerList(orderBy, orderDirection) {
  const apiURL = new URL(`${apiRoot}/customers`);

  // Check if search is active
  if (app.searchActive) {
    apiURL.href += '/search';
    apiURL.searchParams.set('keyword', custSearchInput.value);
  }

  apiURL.searchParams.set('order', orderBy);
  apiURL.searchParams.set('direction', orderDirection);

  const request = await fetch(apiURL, {
    method: 'GET',
  });
  const jsonResponse = await request.json();

  displayCustomerList(jsonResponse);
}

function setTableOrder(table, th, stateData) {
  const ascClass = 'table__order--asc';
  const descClass = 'table__order--desc';

  // Loop through all table heads except target
  // element and remove order class.
  const tableHeads = table.getElementsByClassName('table__order');
  for (let i = 0; i < tableHeads.length; i += 1) {
    if (tableHeads[i] === th) {
      if (th.classList.contains(ascClass)) {
        th.classList.toggle(ascClass, false);
        th.classList.toggle(descClass, true);
      } else if (th.classList.contains(descClass)) {
        th.classList.toggle(ascClass, true);
        th.classList.toggle(descClass, false);
      } else {
        th.classList.toggle(ascClass, true);
        th.classList.toggle(descClass, false);
      }
    } else {
      tableHeads[i].classList.toggle(ascClass, false);
      tableHeads[i].classList.toggle(descClass, false);
    }
  }

  const { order } = th.dataset;
  const direction = th.classList.contains(descClass) ? 'desc' : 'asc';

  if (stateData) {
    stateData.order = order;
    stateData.direction = direction;
  }
}

function displayCustomerInfo(data) {
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

async function getCustomerInfo(apiURL) {
  const request = await fetch(apiURL, { method: 'GET' });
  const response = await request.json();
  displayCustomerInfo(response);
}

function enableCustomerInfoEditMode(state) {
  for (let i = 0; i < custInfoEditFields.length; i += 1) {
    custInfoEditFields[i].toggleAttribute('readonly');
  }
}

/*
  EVENT LISTENERS
*/

for (let i = 0; i < tables.length; i += 1) {
  const table = tables[i];
  table.addEventListener('click', (event) => {
    const { target } = event;

    // Check if event occured on tabel header or table data element
    if (target.closest('th')) {
      const th = target.closest('th'); // Clicked table header
      setTableOrder(table, th, custTable);
      getCustomerList(custTable.order, custTable.direction);
    } else if (target.closest('tr')) {
      const tr = target.closest('tr');
      getCustomerInfo(tr.dataset.href);
    }
  });
}

// On page load event
document.addEventListener('DOMContentLoaded', () => {
  getCustomerList(custTable.order, custTable.direction);
});

// Customer search event
custSearch.addEventListener('submit', (event) => {
  event.preventDefault();

  app.searchActive = true;
  getCustomerList(custTable.order, custTable.direction);
});

custSearchClear.addEventListener('click', () => {
  custSearchInput.value = '';
  app.searchActive = false;
  getCustomerList(custTable.order, custTable.direction);
});

custInfoEditBtn.addEventListener('click', () => {
  enableCustomerInfoEditMode(true);
});
