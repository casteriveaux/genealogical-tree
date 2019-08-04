    <div style="margin-bottom:40px">  
      <h2 <% if (i==0) { %> style="margin-top:0; "<% } %>><a rel="content" href="./?page=ancestry&amp;collection=<%= item.id %>"><%= item.value %></a></h2>
      
      <ul class="nav nav-tabs">
        <li class="active"><a href="#transcriptTab<%= i %>" data-toggle="tab">transcript</a></li>
        <% if (_.findWhere(item.entry.event, {'type' : 'Photo'})) { %><li><a href="#imageTab<%= i %>" data-toggle="tab">image</a></li> <% } %>
        <li><a href="#notesTab<%= i %>" data-toggle="tab" data-tab-href="xhtml/ancestry/<%= item.id %>.html">about</a></li>
      </ul>
      
      <div id="myTabContent<%= i %>" class="tab-content">
        <div id="transcriptTab<%= i %>" class="tab-pane fade in active">
          <table class="table table-hover table-condensed indi">
            <tbody>
              <% if (item.id == "probate") { %>
              
                <tr>
                  <td>Date of Probate</td>
                  <td><%= item.entry[0].date.value %></td>
                </tr>
                <tr>
                  <td>Place of Probate</td>
                  <td>                    
                    <% _.each(item.entry[0].plac.zoom, function(zoom,i) { %>
                        <a href="./?plac=<%= zoom.ref %>" data-plac="<%= zoom.ref %>" target="plac"><%= zoom.value %></a><% if (i != item.entry[0].plac.zoom.length -1) { %>, <% } %> 
                    <% }); %>
                    
                  </td>
                </tr>
                <tr>
                  <td>Entry</td>
                  <td><%= item.entry[0].value %></td>
                </tr>
              
              <% } else { %>

                <% _.each(item.entry.event, function(event,e) { %>
                
                  <% if (event.type != "Photo") { %>
                    <tr>
                      <td><%= event.type %></td>
                      <td>
                        <% if (event.indi) { %>
                        
                          <% if (event.indi.ref == id) { %>
                        
                            <span class="hilight"><%= event.indi.name %></span>
                          
                          <% } else { %>

                            <a target="indi" data-indi="<%= event.indi.ref %>" href="./?indi=<%= event.indi.ref %>"><%= event.indi.name %></a>
                          
                          <% } %> 
                        
                        <% } else if (event.plac) { %>
                        
                          <a target="plac" data-plac="<%= event.plac.ref %>" href="./?plac=<%= event.plac.ref %>"><%= event.plac.name %></a>
                        
                        <% } else { %>
                        
                          <%= event.value %>
                        
                        <% } %>
                      </td>
                    </tr>
                  <% } %>
                  
                <% }); %>

              <% } %>
            </tbody>
          </table>
        </div>
        <div id="notesTab<%= i %>" class="tab-pane fade" style="margin-bottom:60px">
        </div>
        <% if (_.findWhere(item.entry.event, {'type' : 'Photo'})) { %>
          <div id="imageTab<%= i %>" class="tab-pane fade" style="margin-bottom:60px">

          <% _.each(_.where(item.entry.event, {'type' : 'Photo'}), function(event,e) { %>

            <a href="/familytree/certificates/ancestry/<%= item.id %>/<%= item.entry.id %>.jpg" target="img"><img src="/familytree/certificates/ancestry/<%= item.id %>/<%= item.entry.id %>.jpg" style="max-width:100%;max-height:200px; border:1px solid #ddd; padding:5px" /></a>
          
          <% }); %>
          
          
          </div>
        <% } %>
      </div>
      
    </div>
