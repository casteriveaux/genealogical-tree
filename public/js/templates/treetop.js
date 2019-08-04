<ul class="c">
  <li>
    <div>
      <ul class="a">
        <% if (fam && fam.husb) {  %>
        <li>
          <a href="./?tree=<%= fam.husb.ref %>&hilight=<%= indi.hilight %>" rel="content" data-treetop="<%= fam.husb.ref %>" style="display:block;margin-bottom:10px" class="indi">
            <i class="dashicons dashicons-arrow-up-alt2"></i>
          </a>
          <div class="indi m father" rel="content" data-indi="<%= fam.husb.ref %>">
            <% if (fam.husb.contact=='Y') { %>
              <span style="position:absolute; top:-7px; right:-7px;">
                <i class="icon-star"></i>
              </span>
            <% } %>
            <div class="tree-thumbnail">
                <% if (fb==="on" && fam.husb.fb) { %>
                    <img src="http://graph.facebook.com/<%= fam.husb.fb %>/picture" class="img-rounded" />
                <% } else if (tw==="on" && fam.husb.tw) { %>
                    <img src="https://api.twitter.com/1/users/profile_image?size=bigger&amp;screen_name=<%= fam.husb.tw %>" class="img-rounded" />
                <% } else if (fam.husb.image) { %>
                    <img src="<%= fam.husb.image %>" class="img-rounded" />
                <% } %>
            </div>
            <div class="tree-detail"><%= fam.husb.name.first %><br/><% if (fam.husb.name.last!='...') { %><%= fam.husb.name.last %> <% } else { %> &nbsp; <% } %>
              <% if (fam.husb.birt || fam.husb.deat) { %>
                <span>(<%=fam.husb.birt%> - <%=fam.husb.deat%>)</span>
              <% } else { %>
                <span>&nbsp;</span>
              <% } %>
            </div>
          </div>
        </li>
        <% } else { %>
        <li>
          <div class="indi m">
            <div class="tree-thumbnail"></div>
            <div class="tree-detail">...<br>&nbsp;
              <span>&nbsp;</span>
            </div>
          </div>
        </li>
        <% } %>
        <% if (fam && fam.wife) { %>
        <li>
          <a href="./?tree=<%= fam.wife.ref %>&hilight=<%= indi.hilight %>" rel="content" data-treetop="<%= fam.wife.ref %>" style="display:block;margin-bottom:10px" class="indi">
            <i class="dashicons dashicons-arrow-up-alt2"></i>
          </a>
          <div class="indi f mother" rel="content" data-indi="<%= fam.wife.ref %>">
            <% if (fam.wife.contact=='Y') { %>
              <span style="position:absolute; top:-7px; right:-7px;">
                <i class="icon-star"></i>
              </span>
            <% } %>
            <div class="tree-thumbnail">
              <% if (fb==="on" && fam.wife.fb) { %>
                  <img src="http://graph.facebook.com/<%= fam.wife.fb %>/picture" class="img-rounded" />
              <% } else if (tw==="on" && fam.wife.tw) { %>
                  <img src="https://api.twitter.com/1/users/profile_image?size=bigger&amp;screen_name=<%= fam.wife.tw %>" class="img-rounded" />
              <% } else if (fam.wife.image) { %>
                  <img src="<%= fam.wife.image %>" class="img-rounded" />
              <% } %>
            </div>
            <div class="tree-detail"><%= fam.wife.name.first %><br/><% if (fam.wife.name.last!='...') { %><%= fam.wife.name.last %> <% } else { %> &nbsp; <% } %>
              <% if (fam.wife.birt || fam.wife.deat) { %>
                <span>(<%=fam.wife.birt%> - <%=fam.wife.deat%>)</span>
              <% } else { %>
                <span>&nbsp;</span>
              <% } %>
            </div>
          </div>
        </li>
        <% } else { %>
        <li>
          <div class="indi f">
            <div class="tree-thumbnail"></div>
            <div class="tree-detail">...<br>&nbsp;
              <span>&nbsp;</span>
            </div>
          </div>
        </li>
        <% } %>
      </ul>
      <div data-indi="<%=indi.id%>" class="indi <%=indi.sex.toLowerCase()%> o <% if (indi.id == indi.hilight) { %>active<% } %>">
      <% if (indi.contact=='Y') { %>
        <span style="position:absolute; top:-7px; right:-7px;">
          <i class="icon-star"></i>
        </span>
      <% } %>
      <div class="tree-thumbnail">
        <%
          var fbEvent = _.findWhere(indi.event, {'type' : 'facebook'});
          var twEvent = _.findWhere(indi.event, {'type' : 'twitter'});
          var imEvent = _.findWhere(indi.event, {'type' : 'image'});
           if (fb==="on" && fbEvent) { %>
              <img src="http://graph.facebook.com/<%= fbEvent.ref %>/picture" class="img-rounded" />
          <% } else if (tw==="on" && twEvent) { %>
              <img src="https://api.twitter.com/1/users/profile_image?size=bigger&amp;screen_name=<%= twEvent.ref %>" class="img-rounded" />
          <% } else if (imEvent) { %>
              <img src="<%= imEvent.value.event[2].value %>" class="img-rounded" />
          <% }
          %>
      </div>
      <div class="tree-detail">
        <%= indi.name.first %><br/><% if (indi.name.last!='...') { %><%= indi.name.last %> <% } else { %> &nbsp; <% } %>
        <%
          var birt = _.findWhere(indi.event, {'type' : 'birt'});
          var deat = _.findWhere(indi.event, {'type' : 'deat'});
          var birtTxt = "";
          var deatTxt = "";
          if (birt && birt.date)
            birtTxt = birt.date.value.substring(0,4);
          if (deat && deat.date)
            deatTxt = deat.date.value.substring(0,4);
        %>
        <% if ((birt && birt.date) || (deat && deat.date)) { %>
          <span>(<%=birtTxt%> - <%=deatTxt%>)</span>
        <% } else { %>
          <span>&nbsp;</span>
        <% } %>
      </div>
    </div>
    </div>
    <% if (indi.fam) { %>
      <ul class="p">
        <% _.each(indi.fam, function(famId) { %>
        <li id="fam_<%=famId%>" class="pending"><a href="./branch=<%=famId%>/<%=indi.id%>/" target="branch" data-branch="<%=famId%>" data-indi="<%=indi.id%>"><i class="dashicons dashicons-arrow-up-alt2"></i></a></li>
        <% treeBranch(famId,indi.id,indi.hilight) %>
        <% }); %>
      </ul>
    <% } else { %>
        <% treeNoBranch() %>
    <% } %>
  </li>
</ul>