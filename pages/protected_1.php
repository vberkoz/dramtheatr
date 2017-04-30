<?php
include 'checkUser.php';

$result = $db1->query("SELECT * from dt_news");
$news = array();
$i = 0;
while ($row = $result->fetch()) {
  $news[$i]['id'] = $row['id'];
  $news[$i]['tema'] = substr($row['tema'], 0, 200);
  $i++;
}

$result = $db1->query("SELECT * FROM dt_photo");
$photos = array();
$i = 0;
while ($row = $result->fetch()) {
  $photos[$i]['id'] = $row['id'];
  $photos[$i]['photo'] = $row['photo'];
  $photos[$i]['id_act'] = $row['id_act'];
  $photos[$i]['id_vist'] = $row['id_vist'];
  $photos[$i]['id_new'] = $row['id_new'];
  $i++;
}

$result = $db1->query("SELECT * FROM th_news ORDER BY published DESC");
$articles = array();
$i = 0;
while ($row = $result->fetch()) {
	$articles[$i]['id'] = $row['id'];
  if (strlen($row['title']) > 200) {
    $articles[$i]['title'] = substr($row['title'], 0, 200) . "...";
  } else {
    $articles[$i]['title'] = $row['title'];
  }
	$articles[$i]['published'] = $row['published'];
	$i++;
}
?>

<!-- <section class="theatre parallax-window-news"
         data-parallax="scroll"
         data-image-src="/img/news/news-bg.jpg"> -->
<section style="min-height: 635px; background-color: rgba(254, 216, 154, 0.8);">

  <div class="container">
    <div class="row">

      <fieldset style="-webkit-border-radius: 4px;">
        <legend>
          <button id="published" class="btn btn-default" data-toggle="tab" href="#publishedArticles" style="margin-left: 8px;">
            Опубліковані
          </button>
          <button id="create" class="btn btn-default" data-toggle="tab" href="#createArticle">
            Створити
          </button>
          <script type="text/javascript">
            $("#published").click(function() {
              $(".saveArticle, .publishArticle").hide();
            });
            $("#create").click(function() {
              $(".publishArticle").show();
              $("#createTitle").val("");
              $("#createShortContent").summernote('code', '');
              $("#createFullContent").summernote('code', '');
            });
          </script>
          <input type="button" class="btn btn-default pull-right saveArticle"
                 value="Зберегти" style="margin-right: 8px; display: none;" data-toggle="tab"
                 href="#publishedArticles" articleId="">
          <input type="button" class="btn btn-default pull-right publishArticle"
                 value="Публікувати" style="margin-right: 8px; display: none;" data-toggle="tab"
                 href="#publishedArticles">
        </legend>
        <div class="tab-content" style="-webkit-border-radius: 4px;">
          <div id="publishedArticles" class="tab-pane fade in active">
            <table class="table">
              <tbody id="refreshTable">
                <?php foreach ($articles as $article): ?>
                <tr>
                  <td insertAfterById="<?php echo $article['id']; ?>"><div
                       style="background:url('img/b6.jpg')center/cover;
                              width: 40px;
                              height: 36px;
                              -webkit-border-radius: 4px;">
                  </div></td>
                  <td refreshById="<?php echo $article['id']; ?>"><?php echo $article['title']; ?></td>
                  <td refreshById="<?php echo $article['id']; ?>" style="width: 90px;"><?php echo $article['published']; ?></td>
                  <td>
                    <button class="btn btn-default glyphicon glyphicon-pencil"
                            data-toggle='tab'
                            href="#editArticle"
                            getArticleById="<?php echo $article['id']; ?>">
                    </button>
                  </td>
                  <td>
                    <button class="btn btn-danger glyphicon glyphicon-trash"
                            data-toggle='modal'
                            data-target='#myModal'
                            deleteArticleById="<?php echo $article['id']; ?>">
                    </button>
                    <script type="text/javascript">
                      $(".glyphicon-trash").click(function() {
                        var deleteArticleById = $(this).attr("deleteArticleById");
                        $("#continueDelete").attr("deleteArticleById", deleteArticleById);
                      });
                    </script>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div id="createArticle" class="tab-pane fade">
            <div class="col-md-6">
              <label>Тема новини:</label>
              <input id="createTitle"
                     type="text"
                     class="form-control input-edge"
                     style="margin-bottom: 10px;">
              <label>Дата:</label>
              <input id="createDate"
                     type="date"
                     class="form-control"
                     style="margin-bottom: 10px;">
              <label>Коротка стаття:</label>
              <div id="createShortContent"></div>
            </div>
            <div class="col-md-6" max-height="100px;">
              <label>Головне зображення:</label>
              <div id="createMainImage"
                   class="btn"
                   data-toggle='modal'
                   data-target='#ImageModal'
                   style="background:url('img/b6.jpg')center/cover;
                          width: 100%;
                          height: 384px;
                          margin-bottom: 10px;
                          -webkit-border-radius: 4px;">
              </div>
            </div>
            <div class="col-md-12">
              <label>Повна стаття:</label>
              <div id="createFullContent"></div>
              <input type="button"
                     class="btn btn-default pull-right publishArticle"
                     value="Публікувати"
                     style="margin-bottom: 10px;"
                     data-toggle="tab"
                     href="#publishedArticles">
              <script type="text/javascript">
                $(".publishArticle").click(function() {
                  var publishArticle = $(".publishArticle").val();
                  var createTitle = $("#createTitle").val();
                  var createDate = $("#createDate").val();
                  var createShortContent = $("#createShortContent").summernote('code');
                  var createFullContent = $("#createFullContent").summernote('code');
                  $(".publishArticle").hide();
                  $.ajax({
                    url: "teatradmin/ajaxHandler.php",
                    async: false,
                    method: "POST",
                    data: {
                      publishArticle: publishArticle,
                      createTitle: createTitle,
                      createDate: createDate,
                      createShortContent: createShortContent,
                      createFullContent: createFullContent,
                    },
                    dataType: "html",
                    success: function(data) {
                      $("#snackbar").text(data);
                    }
                  });
                  $.ajax({
                    url: "teatradmin/ajaxHandler.php",
                    method: "POST",
                    data: {
                      refreshArticleList: true,
                    },
                    dataType: "html",
                    success: function(data) {
                      $("#refreshTable").html(data);
                    }
                  });
                  var x = document.getElementById("snackbar");
                  x.className = "show";
                  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                });
              </script>
            </div>
          </div>
          <div id="editArticle" class="tab-pane fade"></div>
        </div>
      </fieldset>

      <!-- Delete modal -->
      <div id="myModal"
           class="modal fade"
           tabindex="-1"
           role="dialog"
           aria-labelledby="myModalLabel">
        <div class="modal-dialog"
             role="document">
          <div class="modal-content"
               style="color: black;">
            <div class="modal-header">
              <button type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 id="myModalLabel"
                  class="modal-title">Видалити новину</h4>
            </div>
            <div class="modal-body">
              <div id="modal-text"></div>
              Зміни неможливо відмінити. Бажаєте продовжити?
            </div>
            <div class="modal-footer">
              <button type="button"
                      class="btn btn-default"
                      data-dismiss="modal">Ні</button>
              <button id="continueDelete"
                      type="button"
                      class="btn btn-danger"
                      data-dismiss="modal"
                      deleteArticleById="">Так</button>
                      <script type="text/javascript">
                      $("#continueDelete").click(function() {
                        var deleteArticleById = $(this).attr("deleteArticleById");
                        $.ajax({
                          url: "teatradmin/ajaxHandler.php",
                          method: "POST",
                          data: {
                            deleteArticleById: deleteArticleById,
                          },
                          dataType: "html",
                          success: function(data) {
                            $("#snackbar").text(data);
                          }
                        });
                        $.ajax({
                          url: "teatradmin/ajaxHandler.php",
                          method: "POST",
                          data: {
                            refreshArticleList: true,
                          },
                          dataType: "html",
                          success: function(data) {
                            $("#refreshTable").html(data);
                          }
                        });
                        var x = document.getElementById("snackbar");
                        x.className = "show";
                        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
                      });
                      </script>
            </div>
          </div>
        </div>
      </div>

      <!-- News list -->
      <!-- <fieldset style="-webkit-border-radius: 4px;">
        <legend><h2>Опубліковані новини</h2></legend>
        <legend>
          <button class="btn btn-default">
            Опубліковані
          </button>
          <button class="btn btn-default">
            Створити
          </button>
        </legend>
        <table class="table">
          <tbody>
            <?php //foreach ($articles as $article): ?>
            <tr>
              <td><div
                   class="btn"
                   style="background:url('img/b6.jpg')center/cover;
                          width: 40px;
                          height: 36px;
                          -webkit-border-radius: 4px;">
              </div></td>
              <td><?php //echo $article['title']; ?></td>
              <td><?php //echo $article['published']; ?></td>
              <td>
                <button class="btn btn-default glyphicon glyphicon-pencil"
                        data-toggle='modal'
                        data-target='#editModal'>
                </button>
              </td>
              <td>
                <button class="btn btn-danger glyphicon glyphicon-trash"
                        data-toggle='modal'
                        data-target='#myModal'>
                </button>
              </td>
            </tr>
            <?php //endforeach; ?>
          </tbody>
        </table>
      </fieldset> -->


      <!-- Article edit modal -->
      <div id="editModal"
           class="modal fade"
           tabindex="-1"
           role="dialog"
           aria-labelledby="ImageModalLabel">
        <div class="modal-dialog modal-lg"
             role="document">
          <div class="modal-content"
               style="color: black;">
            <div class="modal-header">
              <button type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 id="ImageModalLabel"
                  class="modal-title">Редагування новини</h4>
            </div>
            <div class="modal-body" style="min-height: 530px;">
              <form id="form1" >
                <div class="col-md-6">
                  <label>Тема новини:</label>
                  <input id="tema"
                         type="text"
                         class="form-control input-edge"
                         style="margin-bottom: 10px;">
                  <label>Дата:</label>
                  <input id="date"
                         type="date"
                         class="form-control"
                         style="margin-bottom: 10px;">
                  <label>Коротка стаття:</label>
                  <textarea class="form-control" rows="10"></textarea>
                </div>
                <div class="col-md-6" max-height="100px;" style="margin-bottom: 10px;">
                  <label>Повна стаття:</label>
                  <textarea class="form-control" rows="17"></textarea>
                </div>
                <div class="col-md-12">
                  <div><label>Зображення:</label></div>
                  <div class="btn"
                       style="background:url('img/b6.jpg')center/cover;
                              width: 85px;
                              height: 85px;
                              margin-right: 5px;
                              margin-bottom: 5px;
                              -webkit-border-radius: 4px;">
                  </div>
                  <div class="btn btn-default"
                       style="width: 85px;
                              height: 85px;
                              margin-right: 5px;
                              margin-bottom: 5px;
                              padding-top: 30px;
                              -webkit-border-radius: 4px;">
                    Додати
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button"
                      class="btn btn-default"
                      data-dismiss="modal">Відмінити</button>
              <button id="editConfirm"
                      type="button"
                      class="btn btn-primary"
                      data-dismiss="modal">Публікувати</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Add news -->
      <!-- <fieldset style="-webkit-border-radius: 4px;">
        <legend><h2>Додати:</h2></legend>
        <form id="form1" >
          <div class="col-md-6">
            <label>Тема новини:</label>
            <input id="tema"
                   type="text"
                   class="form-control input-edge"
                   style="margin-bottom: 10px;">
            <label>Дата:</label>
            <input id="date"
                   type="date"
                   class="form-control"
                   style="margin-bottom: 10px;">
            <label>Коротка стаття:</label>
            <div id="short_content"></div>
          </div>
          <div class="col-md-6" max-height="100px;">
            <label>Головне зображення:</label>
            <div id="main-image"
                 class="btn"
                 data-toggle='modal'
                 data-target='#ImageModal'
                 style="background:url('img/b6.jpg')center/cover;
                        width: 100%;
                        height: 384px;
                        margin-bottom: 10px;
                        -webkit-border-radius: 4px;">
            </div>
          </div>
          <div class="col-md-12">
            <label>Повна стаття:</label>
            <div id="full_content"></div>
            <input id="add_news"
                   type="button"
                   class="btn btn-default"
                   value="Публікувати"
                   style="margin-bottom: 10px;">
          </div>
        </form>
      </fieldset> -->

      <!-- Edit news -->
      <!-- <fieldset style="-webkit-border-radius: 4px;">
        <legend><h2>Змінити:</h2></legend>
         <div class="dropdown">
           <button id="dropdownMenu2"
                   class="btn btn-default dropdown-toggle"
                   type="button"
                   data-toggle="dropdown"
                   aria-haspopup="true"
                   aria-expanded="true"
                   style="margin-bottom: 10px; margin-left: 15px;">
               Вибрати статтю
             <span class="caret"></span>
           </button>
           <ul class="dropdown-menu"
               aria-labelledby="dropdownMenu2">
             <?php //foreach ($news as $new): ?>
               <li>
                 <a class='dropdown-edit'
                      data-id='<?php //echo $new['id']; ?>'
                      style='cursor: pointer;'>
                   <?php //echo $new['tema']; ?>
                 </a>
               </li>
             <?php //endforeach; ?>
           </ul>
         </div>
         <div id="redaktirov"></div>
         <div class="col-md-12">
           <input id="red"
                  type="button"
                  class="btn btn-default"
                  value="Зберегти"
                  style="display: none; margin-bottom: 10px;">
         </div>
      </fieldset> -->

      <!-- Delete news -->
      <!-- <fieldset style="-webkit-border-radius: 4px;">
        <legend><h2>Видалити:</h2></legend>
        <div class="dropdown col-md-12">
          <button id="dropdownMenu1"
                  class="btn btn-default dropdown-toggle"
                  type="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="true"
                  style="margin-bottom: 10px;">
              Вибрати статтю
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu"
              aria-labelledby="dropdownMenu1">
            <?php //foreach ($news as $new): ?>
              <li>
                <a class='dropdown-ref'
                     data-toggle='modal'
                     data-target='#myModal'
                     style='cursor: pointer;'
                     value='<?php //echo $new['id']; ?>'>
                  <?php //echo $new['tema']; ?>
                </a>
              </li>
            <?php //endforeach; ?>
          </ul>
        </div>
      </fieldset> -->

    </div><!-- end row -->
  </div><!-- end container -->
</section><!-- end theatre -->

<!-- Image load modal -->
<div id="ImageModal"
     class="modal fade"
     tabindex="-1"
     role="dialog"
     aria-labelledby="ImageModalLabel">
  <div class="modal-dialog modal-lg"
       role="document">
    <div class="modal-content"
         style="color: black;">
      <div class="modal-header">
        <button type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 id="ImageModalLabel"
            class="modal-title">Виберіть головне зображення</h4>
      </div>
      <div class="modal-body">
        <!-- <div> -->

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 10px;">
            <li role="presentation"
                class="active"><a href="#workers"
                                  aria-controls="workers"
                                  role="tab"
                                  data-toggle="tab"
                                  style="color: #337ab7;">Працівники</a></li>
            <li role="presentation"><a href="#spectacles"
                                       aria-controls="spectacles"
                                       role="tab"
                                       data-toggle="tab"
                                       style="color: #337ab7;">Вистави</a></li>
            <li role="presentation"><a href="#news"
                                       aria-controls="news"
                                       role="tab"
                                       data-toggle="tab"
                                       style="color: #337ab7;">Новини</a></li>
          </ul>

          <!-- Tab panes -->
          <?php //$photos = getAllPhotos(); ?>
          <div class="tab-content">
            <div role="tabpanel"
                 class="tab-pane active"
                 id="workers">
                 <?php foreach ($photos as $photo): ?>
                 <?php if (intval($photo['id_act']) > 0): ?>
                   <div class="btn image-grid-item"
                        data-id="<?php echo $photo['id']; ?>"
                        data-filename="<?php echo $photo['photo']; ?>"
                        style="background:url('img/<?php echo $photo['photo']; ?>')center/cover;
                               width:8%;
                               padding-top:8%;
                               margin: 2px;
                               -webkit-border-radius: 4px;
                               border-style: solid;
                               border-width: 5px;">
                   </div>
                 <?php endif; ?>
                 <?php endforeach; ?>
            </div>
            <div role="tabpanel"
                 class="tab-pane"
                 id="spectacles">
                 <?php foreach ($photos as $photo): ?>
                 <?php if (intval($photo['id_vist']) > 0): ?>
                   <div class="btn image-grid-item"
                        data-id="<?php echo $photo['id']; ?>"
                        data-filename="<?php echo $photo['photo']; ?>"
                        style="background:url('img/<?php echo $photo['photo']; ?>')center/cover;
                               width:8%;
                               padding-top:8%;
                               margin: 2px;
                               -webkit-border-radius: 4px;
                               border-style: solid;
                               border-width: 5px;">
                   </div>
                 <?php endif; ?>
                 <?php endforeach; ?>
            </div>
            <div role="tabpanel"
                 class="tab-pane"
                 id="news">
                 <?php foreach ($photos as $photo): ?>
                 <?php if (intval($photo['id_new']) > 0): ?>
                   <div class="btn image-grid-item"
                        data-id="<?php echo $photo['id']; ?>"
                        data-filename="<?php echo $photo['photo']; ?>"
                        style="background:url('img/<?php echo $photo['photo']; ?>')center/cover;
                               width:8%;
                               padding-top:8%;
                               margin: 2px;
                               -webkit-border-radius: 4px;
                               border-style: solid;
                               border-width: 5px;">
                   </div>
                 <?php endif; ?>
                 <?php endforeach; ?>
            </div>
          <!-- </div> -->

        </div>

      </div>
      <div class="modal-footer">
        <button type="button"
                class="btn btn-default"
                data-dismiss="modal">Відмінити</button>
        <button id="image-confirm"
                type="button"
                class="btn btn-primary"
                data-dismiss="modal">Зберегти</button>
      </div>
    </div>
  </div>
</div>
<div id="snackbar">Some text some message..</div>

<script>
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

  $('#createShortContent, #editShortContent').summernote({
  fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '24', '36', '48'],
  toolbar: [
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'clear']],
    ['fontname', ['fontname']],
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['insert', ['video', 'link']],
    // ['insert', ['picture', 'video', 'link']],
    ['table', ['table']],
    ['fullscreen', ['fullscreen', 'codeview']]
  ],
  height: 160,
  focus: false
  });

  $("#createFullContent, #editFullContent").summernote({
  fontSizes: ["8", "9", "10", "11", "12", "14", "16", "18", "24", "36", "48"],
  toolbar: [
    ["style", ["style"]],
    ["font", ["bold", "italic", "underline", "clear"]],
    ["fontname", ["fontname"]],
    ["fontsize", ["fontsize"]],
    ["color", ["color"]],
    ["para", ["ul", "ol", "paragraph"]],
    ['insert', ['video', 'link']],
    // ["insert", ["picture", "video", "link"]],
    ["table", ["table"]],
    ["fullscreen", ["fullscreen", "codeview"]]
  ],
  height: 300,
  focus: false
  });

  $(".glyphicon-pencil").click(function() {
    $(".news").removeClass("active");
    var getArticleById = $(this).attr("getArticleById");
    $(".saveArticle").attr("articleId", getArticleById)
    $(".saveArticle").show();
    $.ajax({
      url: "teatradmin/ajaxHandler.php",
      method: "POST",
      data: {
        getArticleById: getArticleById
      },
      dataType: "html",
      success: function(data) {
        $("#editArticle").html(data);
      }
    });
  });

$(document).ready(function(){

  // Set current date to input
  Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
  });
  $('#createDate, #editDate').val(new Date().toDateInputValue());

  // добавлення новини
  $("#add_news").click(function() {
    var znachtema = $("#tema").val();
    var shortContent = $("#short_content").summernote('code');
    var znachtxt = $("#full_content").summernote('code');
    // var znachphoto = $("#photo").val();
    var znachdate = $("#date").val();
    var photoId = $(".selected-image").attr("data-id");
    $.ajax({
      url: "teatradmin/add_news.php",
      method: "POST",
      data: {
        zminnatema: znachtema,
        shortcontent: shortContent,
        zminnatxt: znachtxt,
        // zminnaphoto:znachphoto,
        zminnadate: znachdate,
        photo_id: photoId
      },
      dataType: "html",
      success: function(data) {
        // alert(data);
        window.location = '/pages/protected_2.php';
      }
    });
  });
  $(".image-grid-item").click(function() {
    $(".image-grid-item").removeClass("selected-image");
    $(this).addClass("selected-image");
  });
  $("#image-confirm").click(function() {
    $("#main-image").css("background", "url('img/" +
    $(".selected-image").attr("data-filename") + "')center/cover");

  });

  // видалення новини
  $("#continue-delete").click(function() {
    var znachid = $("#modal-text").attr("data-id");
    $.ajax({
      url:"teatradmin/del_news.php",
      method:"POST",
      data:{zminnaid:znachid},
      dataType:"html",
      success:function(data) {
        window.location = '/pages/protected_2.php';
      }
    });
  });
  $(".dropdown-ref").click(function() {
    var id = $(this).attr("value");
    var text = $(this).html();
    $("#modal-text").html('"' + text + '"<br><br>');
    $("#modal-text").attr("data-id", id);
  });

  // редагування новини
  $(".dropdown-edit").click(function() {
    var znachid = $(this).attr("data-id");
    $("#red").show();
    $.ajax({
      url : "teatradmin/load_news.php",
      method : "POST",
      data : {zminnaid : znachid},
      dataType : "html",
      success : function(data) {
        $("#redaktirov").html(data);
      }
    });
  });
  $("#red").click(function() {
    var zid = $("#idn").val();
    var ztema = $("#temas").val();
    var editShortContent = $("#edit_short_content").summernote('code');
    var ztxt = $("#edit_full_content").summernote('code');
    // var zphoto = $("#photos").val();
    var zdate = $("#posadas").val();
    $.ajax({
      url:"teatradmin/red_news.php",
      method:"POST",
      data:{
        zmid:zid,
        zmtema:ztema,
        edit_short_content:editShortContent,
        zmtxt:ztxt,
        // zmphoto:zphoto,
        zmdate:zdate
      },
      dataType:"html",
      success:function(data) {
        // alert (data);
        window.location = '/pages/protected_2.php';
      }
    });
  });

});
</script>
<?php include 'footerAdmin.php' ?>
