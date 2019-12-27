console.log("Test");

const testButton = document.getElementById('test-button');
const testOutput = document.getElementById('test-output');

const hostURL = `${window.location.protocol}//${window.location.host}`;

testButton.addEventListener('click', () => {
  getCustomerList();
});

async function getCustomerList() {
  const data = new FormData();
  data.append('order', 'id');

  const url = new URL('/api/customers', hostURL);
  url.searchParams.set('order', 'id');
  url.searchParams.set('direction', 'asc');
  // url.searchParams.set('search', 'Michel');

  console.log(url);

  const response = await fetch(url, { method: 'GET' });

  if (!response.ok) {
    throw new Error('Fetch failed');
  }

  const text = await response.json();
  console.log(text);
  testOutput.innerHTML = JSON.stringify(text);
}
