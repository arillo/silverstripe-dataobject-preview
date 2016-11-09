<!DOCTYPE html>
<html lang="$ContentLocale">
  <body>
    <div style="padding-top: 40px;">

      <% if $DataObject %>
        <% with $DataObject %>
          $renderWith($ClassName)
        <% end_with %>
      <% else %>
        <div style="text-align: center;">
          Object not found or not created yet.
        </div>
      <% end_if %>

    </div>
  </body>
</html>
