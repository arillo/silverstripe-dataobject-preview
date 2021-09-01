<!DOCTYPE html>
<html lang="$ContentLocale">
  <head>
    <% base_tag %>
  </head>
  <body>
    <div class="content content-typography-space">

      <% if $Rendered %>
        $Rendered
      <% else %>
        <div style="text-align: center;">
          Object not found or not created yet.
        </div>
      <% end_if %>

    </div>
  </body>
</html>
