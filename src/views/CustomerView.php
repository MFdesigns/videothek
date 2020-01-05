<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Webdesign AG">
  <meta name="description" content="Videothek Management Seite">
  <title>Videothek | <?php echo $pageTitle; ?></title>
  <link rel="stylesheet" href="/css/customer_style.css">
  <script src="/js/customerScript.js" defer></script>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="header-container">
      <h1 class="page-title title--light">Videothek</h1>
      <!-- Page navigation -->
      <nav class="nav">
        <ul class="nav-list">
          <li>
            <a class="nav-list__item nav-list__item--selected text-sm--light" href="/kunden">
              <img class="nav-list__item-icon" src="/res/icons/placeholder_icon.svg" alt="Kunden Icon"/>
              <span class="nav-list__item-text">Kunden</span>
            </a>
          </li>
          <li>
            <a class="nav-list__item text-sm--light" href="#">
              <img class="nav-list__item-icon" src="/res/icons/placeholder_icon.svg" alt="Videos Icon"/>
              <span class="nav-list__item-text">Videos</span>
            </a>
          </li>
          <li>
            <a class="nav-list__item text-sm--light" href="#">
              <img class="nav-list__item-icon"  src="/res/icons/placeholder_icon.svg" alt="Ausleihen Icon"/>
              <span class="nav-list__item-text">Ausleihen</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <div class="section page-subheader">
      <h2 class="title-lg--accent">Kunden</h2>
      <!-- Customer search -->
      <form id="cust-search" class="search">
        <label for="search__input">Suchen</label>
        <input id="search__input" type="search" placeholder="z. B. Peter" required>
        <button type="button" id="cust-search-clear">Clear</button>
        <button type="submit" id="cust-search-submit" class="search__submit">Go</button>
      </form>
    </div>

    <div class="section customer-list">
      <h3 class="card-title title--dark">Liste</h3>
      <div class="card">
        <table id="customer-list-table" class="table">
          <thead>
            <tr>
              <th class="table__order table-align-end table__order--asc" data-order="id">Nr
                <img src="/res/icons/table_order_icon.svg" alt="" class="order__icon">
              </th>
              <th class="table__order" data-order="name">Vorname
                <img src="/res/icons/table_order_icon.svg" alt="" class="order__icon">
              </th>
              <th class="table__order" data-order="surname">Nachname
                <img src="/res/icons/table_order_icon.svg" alt="" class="order__icon">
              </th>
            </tr>
          </thead>
          <tbody id="customer-list-output"></tbody>
        </table>
      </div>
    </div>

    <div class="section customer-info">
      <h3 class="card-title title--dark">Infos</h3>
      <div class="card">
        <form id="cust-info-form" action="">
          <!-- Customer ID -->
          <div class="cust-info-item">
            <label for="cust-id">KundenNr</label>
            <input id="cust-id" type="number" readonly required>
          </div>
          <!-- Customer title -->
          <div class="cust-info-item">
            <label for="cust-title">Anrede</label>
            <input class="cust-info-item-editable" type="text" id="cust-title" readonly required>
          </div>
          <!-- Customer name -->
          <div class="cust-info-item">
            <label for="cust-name">Vorname</label>
            <input class="cust-info-item-editable" type="text" id="cust-name" readonly required>
          </div>
          <!-- Customer surname -->
          <div class="cust-info-item">
            <label for="cust-surname">Nachname</label>
            <input class="cust-info-item-editable" type="text" id="cust-surname" readonly required>
          </div>
          <!-- Customer birthday -->
          <div class="cust-info-item">
            <label for="cust-birthday">Geburtstag</label>
            <input class="cust-info-item-editable" type="date" id="cust-birthday" readonly required>
          </div>
          <!-- Customer phone -->
          <div class="cust-info-item">
            <label for="cust-phone">Telefon</label>
            <input class="cust-info-item-editable" type="phone" id="cust-phone" readonly required>
          </div>
          <!-- Customer street -->
          <div class="cust-info-item">
            <label for="cust-street">Strasse</label>
            <input class="cust-info-item-editable" type="text" id="cust-street" readonly required>
          </div>
          <!-- Customer street number -->
          <div class="cust-info-item">
            <label for="cust-streetnumber">Strassenummer</label>
            <input class="cust-info-item-editable" type="text" id="cust-streetnumber" readonly required>
          </div>
          <!-- Customer ONRP -->
          <div class="cust-info-item">
            <label for="cust-onrp">ONRP</label>
            <input type="number" id="cust-onrp" readonly required>
          </div>
          <!-- Customer city -->
          <div class="cust-info-item">
            <label for="cust-city">Stadt</label>
            <input type="text" id="cust-city" readonly required>
          </div>

          <button id="cust-edit-btn" type="button">Bearbeiten</button>
          <button id="cust-delete-btn" type="button">Löschen</button>
        </form>
      </div>
    </div>

    <div class="section customer-lendings">
      <h3 class="card-title title--dark">Ausleihen</h3>
      <div class="card"></div>
      <!-- Customer lendings content -->
    </div>
  </main>

  <footer>
    <p>Copyright © Webdesign AG</p>
  </footer>

  <template id="cust-list-search-no-result">
    <tr class="">
      <td colspan="3">Die Suche ergab keine Treffer.</td>
    </tr>
  </template>

  <template id="cust-list-item">
    <tr data-href="">
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </template>

</body>
</html>
