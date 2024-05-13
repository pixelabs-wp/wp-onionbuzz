<script id="advertisingItem" type="text/template">

    <div class="laqm-item-image trigger-edit" data-id="<%=id%>" <% if (image){%>style="background-image: url('<%=image%>');"<%}%>></div>

    <div class="laqm-item-info">
        <div class="laqm-item-title-tools">
            <div class="laqm-item-tools">
                <a class="laqm-btn laqm-btn-tools with-icon trigger-edit" data-id="<%=id%>" href="javascript:void(0);"><span class="icon-ico-pen"></span></a>
                <a class="laqm-btn laqm-btn-tools with-icon trigger-delete" data-id="<%=id%>" href="javascript:void(0);"><span class="icon-ico-delete"></span></a>
            </div>
            <div class="laqm-item-title no-item-title-image trigger-edit " data-id="<%=id%>">
                <%=title%>
            </div>

        </div>
        <div class="laqm-item-stats pull-left">
            <div class="laqm-item-stat-value">Assigned Locations: <%=locations_count%> </div>
            <div class="laqm-item-stat-value">Type:
                <% if (type == 'adsense'){ %>AdSense<% } %>
                <% if (type == 'image'){ %>Image Banner<% } %>
                <% if (type == 'custom'){ %>Custom Code<% } %>
            </div>
        </div>
        <div class="laqm-item-date-author pull-right">
            <%=date_added%> by <%=user_name%>
        </div>
        <div style="clear: both;"></div>
    </div>

</script>