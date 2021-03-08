

<!--套用formValidator驗證機制-->
<form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">


    <!--主題-->
    <div class="form-group">
      <label class="col-sm-2 control-label">
        <{$smarty.const._MD_LOT_LOT_TITLE}>
      </label>
      <div class="col-sm-10">
        <input type="text" name="lot_title" id="lot_title" class="form-control validate[required]" value="<{$lot_title}>" placeholder="<{$smarty.const._MD_LOT_LOT_TITLE}>">
      </div>
    </div>

    <!--說明-->
    <div class="form-group">
      <label class="col-sm-2 control-label">
        <{$smarty.const._MD_LOT_LOT_CONTENT}>
      </label>
      <div class="col-sm-10">
        <{$lot_content_editor}>
      </div>
    </div>

    <!--指導者-->
    <div class="form-group">
      <label class="col-sm-2 control-label">
        <{$smarty.const._MD_LOT_LOT_TEACHER}>
      </label>
      <div class="col-sm-10">
        <input type="text" name="lot_teacher" id="lot_teacher" class="form-control " value="<{$lot_teacher}>" placeholder="<{$smarty.const._MD_LOT_LOT_TEACHER}>">
      </div>
    </div>

    <!--欄位設定-->
    <div class="form-group">
      <label class="col-sm-2 control-label">
        <{$smarty.const._MD_LOT_LOT_COL}>
      </label>
      <div class="col-sm-10">
        <textarea name="lot_col" rows=8 id="lot_col" class="form-control " placeholder="<{$smarty.const._MD_LOT_LOT_COL}>"><{$lot_col}></textarea>
          範例：
        <div class="alert alert-info">
          unicode=學號;t=溫度;h=濕度;pm25=pm2.5;gps=經緯度
        </div>
          <ol>
            <li>每組設定用;隔開。</li>
            <li>=左邊是系統參數名稱，右邊則是中文標題。</li>
            <li>字串中不要有任何空白或換行。</li>
          </ol>
      </div>
    </div>

    <!--相關檔案、圖片-->
    <div class="form-group">
      <label class="col-sm-2 control-label">
        <{$smarty.const._MD_LOT_UP_LOT_SN}>
      </label>
      <div class="col-sm-10">
        <{$up_lot_sn_form}>
      </div>
    </div>

  <div class="text-center">

        <!--開設者-->
        <input type='hidden' name="lot_uid" value="<{$lot_uid}>">

    <{$token_form}>

    <input type="hidden" name="op" value="<{$next_op}>">
    <input type="hidden" name="lot_sn" value="<{$lot_sn}>">
    <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
  </div>
</form>
