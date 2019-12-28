<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Videothek | <?php echo $pageTitle; ?></title>
  <link rel="stylesheet" href="/css/customer_style.css">
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
              <img class="nav-list__item-icon" src="/res/icons/placeholder_icon.svg"/>
              <span class="nav-list__item-text">Kunden</span>
            </a>
          </li>
          <li>
            <a class="nav-list__item text-sm--light" href="#">
              <img class="nav-list__item-icon" src="/res/icons/placeholder_icon.svg"/>
              <span class="nav-list__item-text">Videos</span>
            </a>
          </li>
          <li>
            <a class="nav-list__item text-sm--light" href="#">
              <img class="nav-list__item-icon"  src="/res/icons/placeholder_icon.svg"/>
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
    </div>

    <div class="section customer-list">
      <h3 class="card-title title--dark">Liste</h3>
      <div class="card"></div>
      <!-- Customer list content -->
    </div>

    <div class="section customer-info">
      <h3 class="card-title title--dark">Infos</h3>
      <div class="card"></div>
      <!-- Customer lendings content -->
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

</body>
</html>
