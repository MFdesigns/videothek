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
    <div class="page-subheader">
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
      <!-- Customer info title -->
      <h2 class="section__title title--dark">Infos</h2>

      <!-- Customer info action buttons -->
      <div id="info-action" class="section__action">
        <button class="action-button text--dark" id="cust-edit-btn" type="button">
          <img class="action-button__icon" src="/res/icons/placeholder_icon.svg" alt="Kunde editieren Icon">
          Bearbeiten
        </button>
        <button class="action-button text--dark" id="cust-delete-btn" type="button">
          <img class="action-button__icon" src="/res/icons/placeholder_icon.svg" alt="Kunde löschen Icon">
          Löschen
        </button>
        <button class="action-button text--dark" id="cust-add-btn" type="button">
          <img class="action-button__icon" src="/res/icons/placeholder_icon.svg" alt="Kunde hinzufügen Icon">
          Neuer Kunde
        </button>
      </div>

      <!-- Customer info content -->
      <div class="section__content">
        <form id="cust-info-form" class="info-form" action="" data-href="">
          <div class="info-form__inputs">

            <div class="info-form__item">
              <!-- Customer ID -->
              <label class="text--dark" for="cust-id">KundenNr</label>
              <input name="id" id="cust-id" class="info-form__input text--dark" type="text" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer title -->
              <label class="text--dark" for="cust-title">Anrede</label>
              <input name="title" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-title" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer name -->
              <label class="text--dark" for="cust-name">Vorname</label>
              <input name="name" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-name" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer surname -->
              <label class="text--dark" for="cust-surname">Nachname</label>
              <input name="surname" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-surname" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer birthday -->
              <label class="text--dark" for="cust-birthday">Geburtstag</label>
              <input name="birthday" class="info-form__input text--dark cust-info-item-editable" type="date" id="cust-birthday" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer phone -->
              <label class="text--dark" for="cust-phone">Telefon</label>
              <input name="phone" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-phone" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer street -->
              <label class="text--dark" for="cust-street">Strasse</label>
              <input name="street" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-street" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer street number -->
              <label class="text--dark" for="cust-streetnumber">Strassenummer</label>
              <input name="streetNumber" class="info-form__input text--dark cust-info-item-editable" type="text" id="cust-streetnumber" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer ONRP -->
              <label class="text--dark" for="cust-onrp">ONRP</label>
              <input name="onrp" type="text" id="cust-onrp" class="info-form__input text--dark" readonly required>
            </div>
            <div class="info-form__item">
              <!-- Customer city -->
              <label class="text--dark" for="cust-city">Stadt</label>
              <input name="city" type="text" id="cust-city" class="info-form__input text--dark" readonly required>
            </div>
          </div>

          <div id="info-context-action" class="info-form__action">
            <button class="info-form__action-button text--dark" id="info-save-btn" type="submit">
              <img src="/res/icons/placeholder_icon.svg" alt="" class="info-form__action-button__icon">
              Speichern
            </button>
            <button class="info-form__action-button text--dark" id="info-cancel-btn" type="button">
              <img src="/res/icons/placeholder_icon.svg" alt="" class="info-form__action-button__icon">
              Abbrechen
            </button>
          </div>
        </form>

      </div>
    </div>

    <div class="section customer-lendings">
      <h3 class="section__title title--dark">Ausleihen</h3>
      <div class="section__content"></div>
      <!-- Customer lendings content -->
    </div>
  </main>

  <footer>
    <p>Copyright © Webdesign AG</p>
  </footer>

  <div id="msg-delete-user" class="message-box">
    <div class="dialogue dialogue--error">
      <h1 class="title--light">Wollen Sie den Kunden wirklich löschen?</h1>
      <p class="text--light">#12 Martin Fischer</p>
      <div class="dialogue__buttons">
        <button id="msg-delete-cust-yes" class="dialogue-button text--dark" type="button">Ja, löschen</button>
        <button id="msg-delete-cust-no" class="dialogue-button text--dark" type="button">Abbrechen</button>
      </div>
    </div>
  </div>

  <div class="message-box">
    <div class="dialogue dialogue--warning">
      <h1 class="title--light">Ooops, etwas ist schiefgegangen...</h1>
      <p class="text--light">Bitte kontaktieren Sie den Support.</p>
      <div class="dialogue__buttons">
        <button class="dialogue-button text--dark" type="button">Ok</button>
        <button class="dialogue-button text--dark" type="button">Abbrechen</button>
      </div>
    </div>
  </div>

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
