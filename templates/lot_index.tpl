<{$toolbar}>


<!--顯示表單-->
<{if $now_op=="lot_form"}>
  <{includeq file="$xoops_rootpath/modules/lot/templates/lot_edit_form.tpl"}>
<{/if}>

<!--顯示某一筆資料-->
<{if $now_op=="show_one_lot"}>
  <{if $isAdmin}>
    <{$delete_lot_func}>
  <{/if}>
  <h2><{$lot_title}> <small><{$lot_teacher}> (<{$lot_date}>)</small></h2>
  <!--說明-->
  <div class="alert alert-success">
    <{$lot_content}>
  </div>

  <{if $show_lot_sn_files}>
    <{$show_lot_sn_files}>
  <{/if}>

  <h3>API 範例及說明</h3>

  <div class="alert alert-info">
    <{$xoops_url}>/modules/lot/api.php?sn=<{$lot_sn}>&user=<span style='color: #8e2323;'>使用者</span>&<{$lot_api}>
  </div>
  <ol>
    <li>「使用者」可輸入學號、姓名等資料，請儘可能是「唯一值」，以避免和他人資料混淆。</li>
    <li>除了<span style='color: #8e2323;'>紅色字</span>為可變動的實際傳入值外，其他文字、符號、數值均不可異動。</li>
  </ol>

  <div class="text-right">
    <{if $isAdmin}>
      <a href="javascript:delete_lot_func(<{$lot_sn}>);" class="btn btn-danger"><{$smarty.const._TAD_DEL}></a>
      <a href="<{$xoops_url}>/modules/lot/index.php?op=lot_form&lot_sn=<{$lot_sn}>" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
      <a href="<{$xoops_url}>/modules/lot/index.php?op=lot_form" class="btn btn-primary"><{$smarty.const._TAD_ADD}></a>
    <{/if}>
    <a href="<{$action}>" class="btn btn-success"><{$smarty.const._TAD_HOME}></a>
  </div>

  <{if $log_data}>
    <h3><{$user}>所有紀錄 <small>(共 <{$total}> 筆資料)</small></h3>
    <table class="table table-condensed table-bordered table-hover table-striped">
      <thead>
        <tr class="info">
          <th>使用者</th>
            <{foreach from=$vars item=title}>
              <th><{$title}></th>
            <{/foreach}>
          <th>紀錄日期</th>
        </tr>
      </thead>
      <tbody>
        <{foreach from=$log_data item=log}>
          <tr>
            <td><a href="index.php?lot_sn=<{$log.lot_sn}>&user=<{$log.user}>"><{$log.user}></a></td>

            <{foreach from=$vars key=var item=title}>
              <td><{$log.data.$var.0}></td>
            <{/foreach}>

            <td><{$log.log_date}></td>
          </tr>
        <{/foreach}>
      </tbody>
    </table>
    <{$bar}>
  <{/if}>
<{/if}>

<!--列出所有資料-->
<{if $now_op=="list_lot"}>
  <{includeq file="$xoops_rootpath/modules/lot/templates/lot_list.tpl"}>
<{/if}>
