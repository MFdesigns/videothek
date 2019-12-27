<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Videothek | <?php echo $pageTitle; ?></title>
  <script src="/js/indexScript.js" defer></script>
</head>
<body>

  <h1>Kunden</h1>

  <table>
    <tr>
      <th>KundenNr</th>
      <th>Vorname</th>
      <th>Nachname</th>
    </tr>
    <?php
    if(isset($customerList)):
      foreach ($customerList as $customer):
      ?>
    <tr>
      <td><?php echo $customer["CustId"]; ?></td>
      <td><?php echo $customer["CustName"]; ?></td>
      <td><?php echo $customer["CustSurname"]; ?></td>
    </tr>
      <?php endforeach; ?>
    <?php else: ?>
    <tr>
      <td>Keine Kunden</td>
    </tr>
    <?php endif; ?>
  </table>

  <button id="test-button">Test</button>

  <p id="test-output"></p>

</body>
</html>
