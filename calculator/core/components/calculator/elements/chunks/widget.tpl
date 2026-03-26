<a href="/assets/components/calculator/connector.php?action=mgr/exchange/export" class="x-btn">Экспорт</a>
<form action="/assets/components/calculator/connector.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="mgr/exchange/import">
    <input name="spreadsheet_file" type="file" class="x-form-text x-form-field x-tbar-page-size" required="">
    <button type="submit" class="x-btn">Импорт</button>
</form>
<p>[[+message]]</p>