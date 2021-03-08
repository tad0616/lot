<{if $all_content}>
  <{if $isAdmin}>
    <{$delete_lot_func}>
    
  <{/if}>

  <div id="lot_save_msg"></div>

  <table class="table table-striped table-hover">
    <thead>
      <tr class="info">
        
          <th>
            <!--主題-->
            <{$smarty.const._MD_LOT_LOT_TITLE}>
          </th>
          <th>
            <!--指導者-->
            <{$smarty.const._MD_LOT_LOT_TEACHER}>
          </th>
          <th>
            <!--開設日期-->
            <{$smarty.const._MD_LOT_LOT_DATE}>
          </th>
        <{if $isAdmin}>
          <th><{$smarty.const._TAD_FUNCTION}></th>
        <{/if}>
      </tr>
    </thead>

    <tbody id="lot_sort">
      <{foreach from=$all_content item=data}>
        <tr id="tr_<{$data.lot_sn}>">
          
            <td>
              <!--主題-->
              <a href="<{$action}>?lot_sn=<{$data.lot_sn}>"><{$data.lot_title}></a>
              <{$data.list_file}>
            </td>

            <td>
              <!--指導者-->
              <{$data.lot_teacher}>
            </td>

            <td>
              <!--開設日期-->
              <{$data.lot_date}>
            </td>

          <{if $isAdmin}>
            <td>
              <a href="javascript:delete_lot_func(<{$data.lot_sn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
              <a href="<{$xoops_url}>/modules/lot/index.php?op=lot_form&lot_sn=<{$data.lot_sn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
              <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
            </td>
          <{/if}>
        </tr>
      <{/foreach}>
    </tbody>
  </table>


  <{if $isAdmin}>
    <div class="text-right">
      <a href="<{$xoops_url}>/modules/lot/index.php?op=lot_form" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
    </div>
  <{/if}>

  <{$bar}>
<{else}>
  <div class="jumbotron text-center">
    <{if $isAdmin}>
      <a href="<{$xoops_url}>/modules/lot/index.php?op=lot_form" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
    <{else}>
      <h3><{$smarty.const._TAD_EMPTY}></h3>
    <{/if}>
  </div>
<{/if}>
