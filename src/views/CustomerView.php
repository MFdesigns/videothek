<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Michel Fäh, Dario Romandini, Julian Vogt">
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
      <div class="section__header">
        <!-- Customer info title -->
        <h2 class="section__title title--dark">Infos</h2>

        <!-- Customer info action buttons -->
        <div id="info-action" class="section__action">
          <button class="action-button text--dark" id="cust-edit-btn" type="button" disabled>
            <img class="action-button__icon" src="/res/icons/edit_icon.svg" alt="Kunde editieren Icon">
            Bearbeiten
          </button>
          <button class="action-button text--dark" id="cust-delete-btn" type="button" disabled>
            <img class="action-button__icon" src="/res/icons/delete_icon.svg" alt="Kunde löschen Icon">
            Löschen
          </button>
          <button class="action-button text--dark" id="cust-add-btn" type="button">
            <img class="action-button__icon" src="/res/icons/add_icon.svg" alt="Kunde hinzufügen Icon">
            Neuer Kunde
          </button>
        </div>
      </div>

      <!-- Customer info content -->
      <div class="section__content">
        <form id="cust-info-form" class="info-form" action="" data-href="">
          <div class="info-form__inputs">

            <div class="cust-id info-form__item">
              <!-- Customer ID -->
              <label class="text--dark" for="cust-id__input">KundenNr</label>
              <input name="id" id="cust-id__input" class="info-form__input text--dark" type="text" autocomplete="off" readonly required>
            </div>
            <div class="cust-title info-form__item">
              <!-- Customer title -->
              <label class="text--dark" for="cust-title__input">Anrede</label>
              <select name="title" id="cust-title__input" class="info-form__input text--dark cust-info-item-editable" disabled required>
                <option value="" selected>-</option>
                <option value="Frau">Frau</option>
                <option value="Herr">Herr</option>
              </select>
            </div>
            <div class="cust-name info-form__item">
              <!-- Customer name -->
              <label class="text--dark" for="cust-name__input">Vorname</label>
              <input name="name" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-name__input" autocomplete="off" title="Darf nur aus Buchstaben bestehen und länger als 2 Zeichen sein" pattern="^[^\d]{2,}$" readonly required>
            </div>
            <div class="cust-surname info-form__item">
              <!-- Customer surname -->
              <label class="text--dark" for="cust-surname__input">Nachname</label>
              <input name="surname" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-surname__input" autocomplete="off" title="Darf nur aus Buchstaben bestehen und länger als 2 Zeichen sein" pattern="^[^\d]{2,}$" readonly required>
            </div>
            <div class="cust-birthday info-form__item">
              <!-- Customer birthday -->
              <label class="text--dark" for="cust-birthday__input">Geburtstag</label>
              <input name="birthday" class="info-form__input text--dark cust-info-item-editable" type="date" id="cust-birthday__input" autocomplete="off" readonly required>
            </div>
            <div class="cust-phone info-form__item">
              <!-- Customer phone -->
              <label class="text--dark" for="cust-phone__input">Telefon</label>
              <input name="phone" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-phone__input" autocomplete="off" title="Darf nur aus Zahlen bestehen und 10 Zeichen lang sein" pattern="^[\d]{10}$" readonly required>
            </div>
            <div class="cust-street info-form__item">
              <!-- Customer street -->
              <label class="text--dark" for="cust-street__input">Strasse</label>
              <input name="street" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-street__input" autocomplete="off" title="Darf nur aus Buchstaben bestehen und länger als 2 Zeichen sein" pattern="^[^\d]{2,}$" readonly required>
            </div>
            <div class="cust-street-number info-form__item">
              <!-- Customer street number -->
              <label class="text--dark" for="cust-street-number__input">Strassenummer</label>
              <input name="streetNumber" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-street-number__input" autocomplete="off" title="Muss mit einer Zahl beginnen, eventuell gefolgt von Buchstaben" pattern="^[\d]+[a-zA-Z]*$" readonly required>
            </div>
            <div class="cust-onrp info-form__item">
              <!-- Customer ONRP -->
              <label class="text--dark" for="cust-onrp__input">ONRP</label>
              <input name="onrp" type="text" id="cust-onrp__input" class="info-form__input text--dark" autocomplete="off" readonly required>
            </div>
            <div class="cust-city info-form__item">
              <!-- Customer city -->
              <label class="text--dark" for="cust-city__input">Stadt</label>
              <input name="city" type="text" id="cust-city__input" class="info-form__input text--dark" autocomplete="off" readonly required>
            </div>
          </div>

          <div id="info-context-action" class="info-form__action">
            <button class="info-form__action-button text--dark" id="info-save-btn" type="submit">
              <img src="/res/icons/save_icon.svg" alt="" class="info-form__action-button__icon">
              Speichern
            </button>
            <button class="info-form__action-button text--dark" id="info-cancel-btn" type="button">
              <img src="/res/icons/cancel_icon.svg" alt="" class="info-form__action-button__icon">
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
          <button class="action-button text--dark" id="lend-edit-btn" type="button" disabled>
            <img class="action-button__icon" src="/res/icons/edit_icon.svg" alt="Ausleihe editieren Icon">
            Bearbeiten
          </button>
          <button class="action-button text--dark" id="lend-delete-btn" type="button" disabled>
            <img class="action-button__icon" src="/res/icons/delete_icon.svg" alt="Ausleihe löschen Icon">
            Löschen
          </button>
          <button class="action-button text--dark" id="lend-add-btn" type="button" disabled>
            <img class="action-button__icon" src="/res/icons/add_icon.svg" alt="Ausleihe hinzufügen Icon">
            Neue Ausleihe
          </button>
        </div>
      </div>

      <!-- Customer lendings content -->
      <div class="section__content">
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
        </table>

        <div class="customer-lending-overlay">
          <form id="cust-lending-form">
            <h2>Neue Ausleihe hinzufügen</h2>

            <label for="lend-vidId">VideoNr</label>
            <input id="lend-vidId" name="vidId" type="text" pattern="^[0-9]+$" required>
            <label for="lend-from">Ausleihdatum</label>
            <input id="lend-from" name="from" type="date" required>
            <label for="lend-until">Rückgabedatum</label>
            <input id="lend-until" name="until" type="date">

            <button type="submit" id="lend-save-btn">Speichern</button>
            <button type="button" id="lend-cancel-btn">Abbrechen</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <p class="text--dark">Copyright © <?php echo date("Y"); ?> Michel Fäh, Dario Romandini, Julian Vogt</p>
  </footer>

  <div id="msg-delete-user" class="message-box">
    <div class="dialogue dialogue--error">
      <h1 class="title--light">Wollen Sie den Kunden wirklich löschen?</h1>
      <p id="msg-delete-user-info" class="text--light">Placeholder</p>
      <div class="dialogue__buttons">
        <button id="msg-delete-cust-yes" class="dialogue-button text--dark" type="button">Ja, löschen</button>
        <button id="msg-delete-cust-no" class="dialogue-button text--dark" type="button">Abbrechen</button>
      </div>
    </div>
  </div>

  <div id="msg-delete-lending" class="message-box">
    <div class="dialogue dialogue--error">
      <h1 class="title--light">Wollen Sie die Ausleihe wirklich löschen?</h1>
      <p id="msg-delete-lend-info" class="text--light">Placeholder</p>
      <div class="dialogue__buttons">
        <button id="msg-delete-lend-yes" class="dialogue-button text--dark" type="button">Ja, löschen</button>
        <button id="msg-delete-lend-no" class="dialogue-button text--dark" type="button">Abbrechen</button>
      </div>
    </div>
  </div>

  <template id="cust-list-search-no-result">
    <tr class="">
      <td colspan="3">Die Suche ergab keine Treffer.</td>
    </tr>
  </template>

  <template id="cust-list-item">
    <tr data-href="" data-id="">
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </template>

  <template id="cust-lendings-item">
    <tr data-id="">
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </template>

</body>
</html>
