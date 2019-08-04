               
    <% if (event.type == 'info' || event.type == 'sour' || event.type == 'occu' ) { %>

        <tr>
          <td>
            <% if (i==0) { %>
              <%= description %>
            <%} else { %>
              &#160;
            <% } %>
          </td>
          <td>
              <%= event.value %>
          </td>
        </tr>    
                 
    <% } else if (event.type == 'resi') { %>

        <tr>
          <td>
            <% if (i==0) { %>
              <%= description %>
            <%} else { %>
              &#160;
            <% } %>
          </td>
          <td>
            <% if (event.plac) { %>
              <% _.each(event.plac.zoom, function(zoom,i) { %>
                  <a href="./?plac=<%= zoom.ref %>" data-plac="<%= zoom.ref %>" target="plac"><%= zoom.value %></a><% if (i != event.plac.zoom.length -1) { %>, <% } %> 
              <% }); %>
            <% } %>
          </td>
        </tr>    

    <% } else if (event.type == 'update') { %>

      <tr>
        <td>Submitted By</td>
        <td>
            <a href="./?indi=<%= event.ref %>" data-indi="<%= event.ref %>" target="indi"><%= event.value %></a>
        </td>
      </tr>    
      <tr>
        <td>Last Updated</td>
        <td>
            <%= formatDate( event.date ) %>
        </td>
      </tr>    
      <tr>
        <td>Reference ID</td>
        <td>
            <%= id %>
        </td>
      </tr>    

    <% } else { %>

      <tr>
        <td>Date of <%= description %> </td>
        <td>
          <% if (event.date) { %>
            <%= formatDate( event.date ) %>
          <% } %>
        </td>
      </tr>    
        
      <tr>
        <td>Place of <%= description %></td>
        <td>
          <% if (event.plac) { %>
            <% _.each(event.plac.zoom, function(zoom,i) { %>
                <a href="./?plac=<%= zoom.ref %>" data-plac="<%= zoom.ref %>" target="plac"><%= zoom.value %></a><% if (i != event.plac.zoom.length -1) { %>, <% } %> 
            <% }); %>
          <% } %>
        </td>
      </tr>

    <% } %>
    
