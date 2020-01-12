<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Michel Fäh, Dario Romandini, Julian Vogt">
  <meta name="description" content="Videothek Management Seite">
  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="/res/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/res/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/res/icons/favicon-16x16.png">
  <link rel="manifest" href="/res/icons/site.webmanifest">
  <link rel="mask-icon" href="/res/icons/safari-pinned-tab.svg" color="#2935ce">
  <link rel="shortcut icon" href="/res/icons/favicon.ico">
  <meta name="msapplication-TileColor" content="#2935ce">
  <meta name="msapplication-config" content="/res/icons/browserconfig.xml">
  <meta name="theme-color" content="#2935ce">
  <!-- Title -->
  <title>Videothek | <?php echo $pageTitle; ?></title>
  <!-- Stylesheets -->
  <link rel="stylesheet" href="/css/customer_style.css">
  <!-- Scripts -->
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
              <img class="nav-list__item-icon" src="/res/icons/customers_icon.svg" alt="Kunden Icon"/>
              <span class="nav-list__item-text">Kunden</span>
            </a>
          </li>
          <li>
            <a class="nav-list__item text-sm--light" href="#">
              <img class="nav-list__item-icon" src="/res/icons/video_icon.svg" alt="Videos Icon"/>
              <span class="nav-list__item-text">Videos</span>
            </a>
          </li>
          <li>
            <a class="nav-list__item text-sm--light" href="#">
              <img class="nav-list__item-icon"  src="/res/icons/lending_icon.svg" alt="Ausleihen Icon"/>
              <span class="nav-list__item-text">Ausleihen</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <div class="page-subheader">
      <h2 class="title-lg--accent">Kunden</h2>
      <!-- Customer search -->
      <form id="cust-search" class="search">
        <label for="search__input" class="search__label title--dark">Suchen</label>
        <input id="search__input" class="input" type="search" placeholder="z. B. Peter" autocomplete="off" spellcheck="false" required>
        <button id="cust-search-clear" class="icon-button" type="button">
          <img src="/res/icons/cancel_icon.svg" alt="Suche löschen Icon">
        </button>
        <button type="submit" id="cust-search-submit" class="icon-button">
          <img src="/res/icons/search_icon.svg" alt="Kunden durchsuchen Icon">
        </button>
      </form>
    </div>

    <div class="section customer-list">
      <h3 class="section__title title--dark">Liste</h3>
      <div class="section__content">
        <div class="table-scroll">
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
    </div>

    <div class="section customer-info">
      <div class="section__header">
        <!-- Customer info title -->
        <h2 class="section__title title--dark">Infos</h2>

        <!-- Customer info action buttons -->
        <div id="info-action" class="section__action">
          <button class="action-button button text--dark" id="cust-edit-btn" type="button" disabled>
            <img src="/res/icons/edit_icon.svg" alt="Kunde editieren Icon">
            Bearbeiten
          </button>
          <button class="action-button button text--dark" id="cust-delete-btn" type="button" disabled>
            <img src="/res/icons/delete_icon.svg" alt="Kunde löschen Icon">
            Löschen
          </button>
          <button class="action-button button text--dark" id="cust-add-btn" type="button">
            <img src="/res/icons/add_icon.svg" alt="Kunde hinzufügen Icon">
            Neuer Kunde
          </button>
        </div>
      </div>

      <!-- Customer info content -->
      <div class="section__content">
        <form id="cust-info-form" class="info-form" action="" data-href="">
          <div class="info-form__inputs">
            <!-- Customer id -->
            <label id="cust-id-label" class="text--dark" for="cust-id-input">KundenNr</label>
            <input name="id" id="cust-id-input" class="info-form__input text--dark" type="text" tabindex="-1" autocomplete="off" readonly required>

            <!-- Title -->
            <label id="cust-title-label" class="text--dark" for="cust-title-input">Anrede</label>
            <select name="title" id="cust-title-input" class="info-form__input text--dark cust-info-item-editable" disabled required>
              <option value="" selected>-</option>
              <option value="Frau">Frau</option>
              <option value="Herr">Herr</option>
            </select>

            <!-- Name -->
            <label id="cust-name-label" class="text--dark" for="cust-name-input">Vorname</label>
            <input name="name" id="cust-name-input" class="info-form__input text--dark cust-info-item-editable" type="text" autocomplete="off" title="Darf nur aus Buchstaben bestehen und länger als 2 Zeichen sein" pattern="^[^\d]{2,}$" readonly required>

            <!-- Surname -->
            <label id="cust-surname-label" class="text--dark" for="cust-surname-input">Nachname</label>
            <input name="surname" id="cust-surname-input" class="info-form__input text--dark cust-info-item-editable" type="text" autocomplete="off" title="Darf nur aus Buchstaben bestehen und länger als 2 Zeichen sein" pattern="^[^\d]{2,}$" readonly required>

            <!-- birthday -->
            <label id="cust-birthday-label" class="text--dark" for="cust-birthdaylinput">Geburtstag</label>
            <input name="birthday" id="cust-birthday-input" class="info-form__input text--dark cust-info-item-editable" type="date" autocomplete="off" readonly required>

            <!-- phone -->
            <label id="cust-phone-label" class="text--dark" for="cust-phone-input">Telefon</label>
            <input name="phone" id="cust-phone-input" class="info-form__input text--dark cust-info-item-editable" type="text" autocomplete="off" title="Darf nur aus Zahlen bestehen und 10 Zeichen lang sein" pattern="^[\d]{10}$" readonly required>

            <!-- ONRP -->
            <label id="cust-onrp-label" class="text--dark" for="cust-onrp-input">ONRP</label>
            <input name="onrp" id="cust-onrp-input" type="text" class="info-form__input text--dark" tabindex="-1" autocomplete="off" readonly required>

            <!-- PLZ -->
            <label id="cust-plz-label" class="text--dark" for="cust-plz-input">PLZ</label>
            <div id="plz-search">
              <input id="cust-plz-input" type="text" class="info-form__input text--dark cust-info-item-editable" autocomplete="off" readonly required>
              <button type="button" id="plz-search-btn" class="icon-button">
                <img src="/res/icons/search_icon.svg" alt="Kunden durchsuchen Icon">
              </button>
            </div>

            <!-- City -->
            <label id="cust-city-label" class="text--dark" for="cust-city-input">Stadt</label>
            <select id="cust-city-input" class="info-form__input text--dark cust-info-item-editable" disabled required>
              <option value="" selected>-</option>
            </select>

            <!-- Street -->
            <label id="cust-street-label" class="text--dark" for="cust-street-input">Strasse</label>
            <input name="street" id="cust-street-input" class="info-form__input text--dark cust-info-item-editable" type="text" autocomplete="off" title="Darf nur aus Buchstaben bestehen und länger als 2 Zeichen sein" pattern="^[^\d]{2,}$" readonly required>

            <!-- Street number -->
            <label id="cust-street-number-label" class="text--dark" for="cust-street-number-input">Strassenummer</label>
            <input name="streetNumber" id="cust-street-number-input" class="info-form__input text--dark cust-info-item-editable" type="text" autocomplete="off" title="Muss mit einer Zahl beginnen, eventuell gefolgt von Buchstaben" pattern="^[\d]+[a-zA-Z]*$" readonly required>
          </div>

          <div id="info-context-action" class="info-form__action">
            <button class="info-form__action-button button text--dark" id="info-save-btn" type="submit">
              <img src="/res/icons/save_icon.svg" alt="">
              Speichern
            </button>
            <button class="info-form__action-button button text--dark" id="info-cancel-btn" type="button">
              <img src="/res/icons/cancel_icon.svg" alt="">
              Abbrechen
            </button>
          </div>
        </form>

      </div>
    </div>

    <div class="section customer-lendings">
      <div class="section__header">
        <!-- Customer info title -->
        <h2 class="section__title title--dark">Ausleihen</h2>

        <!-- Customer info action buttons -->
        <div id="lend-action" class="section__action">
          <button class="action-button button text--dark" id="lend-edit-btn" type="button" disabled>
            <img src="/res/icons/edit_icon.svg" alt="Ausleihe editieren Icon">
            Bearbeiten
          </button>
          <button class="action-button button text--dark" id="lend-delete-btn" type="button" disabled>
            <img src="/res/icons/delete_icon.svg" alt="Ausleihe löschen Icon">
            Löschen
          </button>
          <button class="action-button button text--dark" id="lend-add-btn" type="button" disabled>
            <img src="/res/icons/add_icon.svg" alt="Ausleihe hinzufügen Icon">
            Neue Ausleihe
          </button>
        </div>
      </div>

      <!-- Customer lendings content -->
      <div class="section__content">
        <div class="table-scroll">
          <table id="customer-lendings-table" class="table">
            <thead>
              <tr>
                <th class="table__order table-align-end table__order--asc" data-order="vidId">VidNr
                  <img src="/res/icons/table_order_icon.svg" alt="" class="order__icon">
                </th>
                <th class="table__order" data-order="title">Titel
                  <img src="/res/icons/table_order_icon.svg" alt="" class="order__icon">
                </th>
                <th class="table__order" data-order="from">Von
                  <img src="/res/icons/table_order_icon.svg" alt="" class="order__icon">
                </th>
                <th class="table__order" data-order="until">Bis
                  <img src="/res/icons/table_order_icon.svg" alt="" class="order__icon">
                </th>
              </tr>
            </thead>
            <tbody id="customer-lendings-output"></tbody>
        </div>
        </table>

        <div class="customer-lending-overlay">
          <h2 class="title--dark">Neue Ausleihe hinzufügen</h2>
          <form id="cust-lending-form">

            <label for="lend-vidId" class="text--dark">VideoNr</label>
            <input id="lend-vidId" class="input text--dark" name="vidId" type="text" pattern="^[0-9]+$" required>
            <label for="lend-from" class="text--dark">Ausleihdatum</label>
            <input id="lend-from" class="input text--dark"name="from" type="date" required>
            <label for="lend-until" class="text--dark">Rückgabedatum</label>
            <input id="lend-until" class="input text--dark" name="until" type="date">

            <div class="cust-lending-form__actions">
              <button type="submit" id="lend-save-btn" class="icon-button text--dark">
                <img src="/res/icons/save_icon.svg" alt="Ausleihe speichern Icon">
                Speichern
              </button>
              <button type="button" id="lend-cancel-btn" class="icon-button text--dark">
                <img src="/res/icons/cancel_icon.svg" alt="Ausleihe abbrechen Icon">
                Abbrechen
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <p class="text--dark">Copyright © <?php echo date("Y"); ?> Michel Fäh, Dario Romandini, Julian Vogt</p>
  </footer>

  <div class="msg-box">
    <div class="dialogue dialogue--error">
      <h1 class="msg-box__title title--light"></h1>
      <p class="msg-box__content text--light">Placeholder</p>
      <div class="msg-box__buttons">
        <button type="button" id="msg-yes-btn" class="msg-box__button icon-button text--dark">
          <img src="/res/icons/delete_icon.svg" alt="Löschen bestätigen Icon">
          Ja, löschen
        </button>
        <button type="button" id="msg-cancel-btn" class="msg-box__button icon-button text--dark">
          <img src="/res/icons/cancel_icon.svg" alt="Löschen abbrechen Icon">
          Abbrechen
        </button>
      </div>
    </div>
  </div>

  <template id="cust-list-search-no-result">
    <tr class="tr-no-events">
      <td colspan="3">Die Suche ergab keine Treffer.</td>
    </tr>
  </template>

  <template id="cust-list-item">
    <tr data-href="" data-id="">
      <td class="table-align-end"></td>
      <td></td>
      <td></td>
    </tr>
  </template>

  <template id="cust-lendings-item">
    <tr data-id="">
      <td class="table-align-end"></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </template>

</body>
</html>
