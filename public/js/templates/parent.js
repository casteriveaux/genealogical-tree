    <% if (indi.father || indi.mother) { %>
      <tr><td colspan='2'>&#160;</td></tr>
    <% } %>
    <% if (indi.father) { %>
      <tr>
        <td>Father</td>
        <td><a href="./?indi=<%= indi.father.ref %>" data-indi="<%= indi.father.ref %>" target="indi"><%= indi.father.name.first %> <%= indi.father.name.last %></a></td>
      </tr>
    <% } %>
    <% if (indi.mother) { %>
      <tr>
        <td>Mother</td>
        <td><a href="./?indi=<%= indi.mother.ref %>" data-indi="<%= indi.mother.ref %>" target="indi"><%= indi.mother.name.first %> <%= indi.mother.name.last %></a></td>
      </tr>
    <% } %>
