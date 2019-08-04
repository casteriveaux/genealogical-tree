    <% if (indi.sibling || indi.halfsibling) { %>
      <tr><td colspan='2'>&#160;</td></tr>
    <% } %>
    <% if (indi.sibling) { %>
      <tr>
        <td>Siblings</td>
        <td>
          <% _.each(indi.sibling, function(item,i) { %>
              <a href="./?indi=<%= item.ref %>" data-indi="<%= item.ref %>" target="indi"><%= item.name %></a><% if (i != indi.sibling.length -1) { %>, <% } %> 
          <% }); %>
        </td>
      </tr>
    <% } %>
    <% if (indi.halfsibling) { %>
      <tr>
        <td>Half Siblings</td>
        <td>
            <% _.each(indi.halfsibling, function(item,i) { %>
                <a href="./?indi=<%= item.ref %>" data-indi="<%= item.ref %>" target="indi"><%= item.name %></a><% if (i != indi.halfsibling.length -1) { %>, <% } %> 
            <% }); %>
        </td>
      </tr>
    <% } %>
