        <% _.each(indi.family, function(item,i) { %>
        
          <tr><td colspan='2'>&#160;</td></tr>
          <tr>
            <td>Spouse 
              <% if (indi.family.length > 1) { %>
              (<%= i+1 %>)
              <% } %>
            </td>
            <td>        
              <a href="./?indi=<%= item.partner.ref %>" data-indi="<%= item.partner.ref %>" target="indi"><%= item.partner.name.first %> <%= item.partner.name.last %></a>
            </td>
          </tr>
          
          <%
            var eventMarr  = _.where(item.event, {type: "marr"});
            var eventSour  = _.where(item.event, {type: "sour"});
            if ( eventMarr[0] || eventSour[0] ) {
          %>
          <%= populateEvent(eventMarr,'Marriage') %>          
          <%= populateEvent(eventSour,'Source') %>          
          <%
            }
          %>
          
          
          <% if (item.child) { %>

            <tr>
              <td>Children</td>
              <td>        
                <% _.each(item.child, function(itemChild,j) { %>
                    <a href="./?indi=<%= itemChild.ref %>" data-indi="<%= itemChild.ref %>" target="indi"><%= itemChild.name %></a><% if (j != item.child.length -1) { %>, <% } %> 
                <% }); %>
              </td>
            </tr>          
          
          <% } %>
          
        <% }); %>
