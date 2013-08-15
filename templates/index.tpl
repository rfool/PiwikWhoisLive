{literal}
<script type="text/javascript">
$(document).ready(function() {
	$("#WhoisLive_unknownOnly").change( function() {
		var checked = $(this).is(":checked");
		$(this).closest('.widgetContent')
			.html( '<img src="themes/default/images/loading-blue.gif" />' )
			.load( 'index.php?module=WhoisLive&action=widget{/literal}&idSite={$idSite}&period={$period}&date={$date}{literal}&unknownOnly=' + (checked?'1':'0') );
	} );
	$("#WhoisLive_visits .truncated").truncate( 30 ).tooltip();
	$("#WhoisLive_visits tbody tr").addClass("subDataTable").click( function() {
		$('#WhoisLive_tr').remove();
		$(this).after( '<tr id="WhoisLive_tr"><td id="WhoisLive_result" colspan="3" style="padding:10px 10px; font-family:monospace; font-size:10px; white-space:pre; overflow:auto; width:100%"></td></tr>' );
		$('#WhoisLive_result')
			.html( '<img src="themes/default/images/loading-blue.gif" />' )
			.load( 'index.php?module=WhoisLive&action=getWhoisFromIp{/literal}&idSite={$idSite}&period={$period}&date={$date}{literal}&ip=' + $(this).attr("ip"), function() {
				$('#WhoisLive_result').prepend( '<img id="WhoisLive_close" src="themes/default/images/close.png" style="float:right; margin:3px; cursor:pointer" />' );
				$('#WhoisLive_close').click( function() {
					$('#WhoisLive_tr').remove();
				} );
			} )
			;
	} );
} );
</script>
{/literal}

<div style="text-align:right">
	<input type="checkbox" name="unknownOnly" id="WhoisLive_unknownOnly" value="1" {if $unknownOnly eq 1}checked="checked"{/if} />
	<label for="WhoisLive_unknownOnly"> visitors from unknown ips only</label>
</div>

<table cellpadding="2" cellspacing="0" class="dataTable" id="WhoisLive_visits">
<thead>
	<tr>
		<th width="25%">IP<br/>Provider<br/>Country/City</th>
		<th width="55%">OS/Resolution<br/>Browser<br/>Referer/Keywords</th>
		<th width="20%">Last Action<br/>Pages/Dur.</th>
	</tr>
</thead>
<tbody>
	{foreach from=$visits item=row}
		<tr ip="{$row.ip}">
			<td style="overflow:hidden">
				{$row.ip}<br/>
				{$row.location_provider|escape:"html"}<br/>
				{$row.location_geoip_country|escape:"html"} / {$row.location_geoip_city|escape:"html"}
			</td>
			<td style="overflow:hidden">
				<img src="{$row.config_os_logo|escape:"urlpathinfo"}" /> {$row.config_os|escape:"html"} / {$row.config_resolution|escape:"html"}<br />
				<img src="{$row.config_browser_logo|escape:"urlpathinfo"}" /> {$row.config_browser_name|escape:"html"}<br/>
				{if $row.referer_type eq 1}
					<i>direct</i>
				{elseif $row.referer_type eq 2}
					<img src="{$row.referer_logo|escape:"urlpathinfo"}" /> {$row.referer_name|escape:"html"}: <span class="truncated">{$row.referer_keyword|escape:"html"}</span>
				{elseif $row.referer_type eq 3}
					<span class="truncated">{$row.referer_url|escape:"html"}</span>
				{elseif $row.referer_type eq 6}
					<i>campaign</i>
				{/if}
			</td>
			<td>
				{$row.visit_last_action_time}<br/>
				{$row.visit_total_actions} / {$row.visit_total_time} s
			</td>
		</tr>
	{/foreach}
</tbody>
</table>

