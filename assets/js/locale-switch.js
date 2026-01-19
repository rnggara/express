$("#change_locale").change(function(){
    $.ajax({
        url : window.HOST_URL+"/locale-switch",
        type : "post",
        dataType : "json",
        data : {
            locale : $(this).val()
        },
        success : function(resp){
            location.reload()
        }
    })
})

$("[data-toggle=upload_file]").change(function(){
    var parent = $(this).parents("div.upload-file")
    var label = $(parent).find(".upload-file-label")
    console.log(parent)
    console.log(label)
    label.text($(this).val().split('\\').pop())
    label.addClass("text-primary")
})
