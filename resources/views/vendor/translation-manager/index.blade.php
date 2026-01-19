@extends('layouts.template')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/css/bootstrap.min.css" integrity="sha512-F7WyTLiiiPqvu2pGumDR15med0MDkUIo5VTVyyfECR5DZmCnDhti9q5VID02ItWjq6fvDfMaBaDl2J3WdL1uxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/poshytip/1.2/tip-skyblue/tip-skyblue.min.css" integrity="sha512-0xuxtZvZDaJUhA6GHvu54/dhA2scceFWfBxC4B4g1fVGqQSngoZ6zR4m/reuRRjLzLrmQBXjyyFZ1rUxWAxkDQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
{{-- <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet"/> --}}
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<style>
    a.status-1{
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="card card-custom gutter-b card-stretch">
    <div class="card-header">
        <h3 class="card-title">Translation Manager</h3>
        <div class="card-toolbar">
            <div class="btn-group">

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            {{-- <p>Warning, translations are not visible until they are exported back to the app/lang file, using <code>php artisan translation:export</code> command or publish button.</p> --}}
            <p>Warning, translations are not visible until the are exported back.</p>
            <div class="alert alert-success success-import" style="display:none;">
                <p>Done importing, processed <strong class="counter">N</strong> items! Reload this page to refresh the groups!</p>
            </div>
            <div class="alert alert-success success-find" style="display:none;">
                <p>Done searching for translations, found <strong class="counter">N</strong> items!</p>
            </div>
            <div class="alert alert-success success-publish" style="display:none;">
                <p>Done publishing the translations for group '<?php echo $group ?>'!</p>
            </div>
            <div class="alert alert-success success-publish-all" style="display:none;">
                <p>Done publishing the translations for all group!</p>
            </div>
            <?php if(Session::has('successPublish')) : ?>
                <div class="alert alert-info">
                    <?php echo Session::get('successPublish'); ?>
                </div>
            <?php endif; ?>
            <p>
                @if (!isset($group))
                    <form class="form-import" method="POST" action="<?php echo action('\Barryvdh\TranslationManager\Controller@postImport') ?>" data-remote="true" role="form">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                    <select name="replace" class="form-control">
                                        <option value="0">Append new translations</option>
                                        <option value="1">Replace existing translations</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                <button type="submit" class="btn btn-success btn-block"  data-disable-with="Loading..">Import groups</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form class="form-find" method="POST" action="<?php echo action('\Barryvdh\TranslationManager\Controller@postFind') ?>" data-remote="true" role="form" data-confirm="Are you sure you want to scan you app folder? All found translation keys will be added to the database.">
                        <div class="form-group">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <button type="submit" class="btn btn-info" data-disable-with="Searching.." >Find translations in files</button>
                        </div>
                    </form>
                @endif
                @if (isset($group))
                    <form class="form-inline form-publish" method="POST" action="<?php echo action('\Barryvdh\TranslationManager\Controller@postPublish', $group) ?>" data-remote="true" role="form" data-confirm="Are you sure you want to publish the translations group '<?php echo $group ?>? This will overwrite existing language files.">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <button type="submit" class="btn btn-info" data-disable-with="Publishing.." >Publish translations</button>
                        <a href="<?= action('\Barryvdh\TranslationManager\Controller@getIndex') ?>" class="btn btn-default">Back</a>
                    </form>
                @endif
            </p>
            <form role="form" method="POST" action="<?php echo action('\Barryvdh\TranslationManager\Controller@postAddGroup') ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="form-group">
                    <p>Choose a group to display the group translations. If no groups are visisble, make sure you have run the migrations and imported the translations.</p>
                    <select name="group" id="group" class="form-control group-select">
                        <?php foreach($groups as $key => $value): ?>
                            <option value="<?php echo $key ?>"<?php echo $key == $group ? ' selected':'' ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Enter a new group name and start edit translations in that group</label>
                    <input type="text" class="form-control" name="new-group" />
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-default" name="add-group" value="Add and edit keys" />
                </div>
            </form>
            @if ($group)
                @include("vendor.translation-manager._group")
            @else
                @include("vendor.translation-manager._main")
            @endif
        </div>
    </div>
</div>
@endsection


@section('custom_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/poshytip/1.2/jquery.poshytip.min.js" integrity="sha512-/WRL6m0vrZwChpCDXLzRVpFqM5FqmF0k5A7NWQ+HvhtUtREs8MemDuFcMfEHZqg53UJmWbrs9aAWl6cDwdDv6w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.bundle.min.js" integrity="sha512-PqRelaJGXVuQ81N6wjUrRQelCDR7z8RvKGiR9SbSxKHPIt15eJDmIVv9EJgwq0XvgylszsjzvQ0+VyI2WtIshQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script> --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>

if (typeof jQuery != 'undefined') {
    // jQuery is loaded => print the version
    console.log(jQuery.fn.jquery);
}

    (function(e,t){if(e.rails!==t){e.error("jquery-ujs has already been loaded!")}var n;var r=e(document);e.rails=n={linkClickSelector:"a[data-confirm], a[data-method], a[data-remote], a[data-disable-with]",buttonClickSelector:"button[data-remote], button[data-confirm]",inputChangeSelector:"select[data-remote], input[data-remote], textarea[data-remote]",formSubmitSelector:"form",formInputClickSelector:"form input[type=submit], form input[type=image], form button[type=submit], form button:not([type])",disableSelector:"input[data-disable-with], button[data-disable-with], textarea[data-disable-with]",enableSelector:"input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled",requiredInputSelector:"input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",fileInputSelector:"input[type=file]",linkDisableSelector:"a[data-disable-with]",buttonDisableSelector:"button[data-remote][data-disable-with]",CSRFProtection:function(t){var n=e('meta[name="csrf-token"]').attr("content");if(n)t.setRequestHeader("X-CSRF-Token",n)},refreshCSRFTokens:function(){var t=e("meta[name=csrf-token]").attr("content");var n=e("meta[name=csrf-param]").attr("content");e('form input[name="'+n+'"]').val(t)},fire:function(t,n,r){var i=e.Event(n);t.trigger(i,r);return i.result!==false},confirm:function(e){return confirm(e)},ajax:function(t){return e.ajax(t)},href:function(e){return e.attr("href")},handleRemote:function(r){var i,s,o,u,a,f,l,c;if(n.fire(r,"ajax:before")){u=r.data("cross-domain");a=u===t?null:u;f=r.data("with-credentials")||null;l=r.data("type")||e.ajaxSettings&&e.ajaxSettings.dataType;if(r.is("form")){i=r.attr("method");s=r.attr("action");o=r.serializeArray();var h=r.data("ujs:submit-button");if(h){o.push(h);r.data("ujs:submit-button",null)}}else if(r.is(n.inputChangeSelector)){i=r.data("method");s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else if(r.is(n.buttonClickSelector)){i=r.data("method")||"get";s=r.data("url");o=r.serialize();if(r.data("params"))o=o+"&"+r.data("params")}else{i=r.data("method");s=n.href(r);o=r.data("params")||null}c={type:i||"GET",data:o,dataType:l,beforeSend:function(e,i){if(i.dataType===t){e.setRequestHeader("accept","*/*;q=0.5, "+i.accepts.script)}if(n.fire(r,"ajax:beforeSend",[e,i])){r.trigger("ajax:send",e)}else{return false}},success:function(e,t,n){r.trigger("ajax:success",[e,t,n])},complete:function(e,t){r.trigger("ajax:complete",[e,t])},error:function(e,t,n){r.trigger("ajax:error",[e,t,n])},crossDomain:a};if(f){c.xhrFields={withCredentials:f}}if(s){c.url=s}return n.ajax(c)}else{return false}},handleMethod:function(r){var i=n.href(r),s=r.data("method"),o=r.attr("target"),u=e("meta[name=csrf-token]").attr("content"),a=e("meta[name=csrf-param]").attr("content"),f=e('<form method="post" action="'+i+'"></form>'),l='<input name="_method" value="'+s+'" type="hidden" />';if(a!==t&&u!==t){l+='<input name="'+a+'" value="'+u+'" type="hidden" />'}if(o){f.attr("target",o)}f.hide().append(l).appendTo("body");f.submit()},formElements:function(t,n){return t.is("form")?e(t[0].elements).filter(n):t.find(n)},disableFormElements:function(t){n.formElements(t,n.disableSelector).each(function(){n.disableFormElement(e(this))})},disableFormElement:function(e){var t=e.is("button")?"html":"val";e.data("ujs:enable-with",e[t]());e[t](e.data("disable-with"));e.prop("disabled",true)},enableFormElements:function(t){n.formElements(t,n.enableSelector).each(function(){n.enableFormElement(e(this))})},enableFormElement:function(e){var t=e.is("button")?"html":"val";if(e.data("ujs:enable-with"))e[t](e.data("ujs:enable-with"));e.prop("disabled",false)},allowAction:function(e){var t=e.data("confirm"),r=false,i;if(!t){return true}if(n.fire(e,"confirm")){r=n.confirm(t);i=n.fire(e,"confirm:complete",[r])}return r&&i},blankInputs:function(t,n,r){var i=e(),s,o,u=n||"input,textarea",a=t.find(u);a.each(function(){s=e(this);o=s.is("input[type=checkbox],input[type=radio]")?s.is(":checked"):s.val();if(!o===!r){if(s.is("input[type=radio]")&&a.filter('input[type=radio]:checked[name="'+s.attr("name")+'"]').length){return true}i=i.add(s)}});return i.length?i:false},nonBlankInputs:function(e,t){return n.blankInputs(e,t,true)},stopEverything:function(t){e(t.target).trigger("ujs:everythingStopped");t.stopImmediatePropagation();return false},disableElement:function(e){e.data("ujs:enable-with",e.html());e.html(e.data("disable-with"));e.bind("click.railsDisable",function(e){return n.stopEverything(e)})},enableElement:function(e){if(e.data("ujs:enable-with")!==t){e.html(e.data("ujs:enable-with"));e.removeData("ujs:enable-with")}e.unbind("click.railsDisable")}};if(n.fire(r,"rails:attachBindings")){e.ajaxPrefilter(function(e,t,r){if(!e.crossDomain){n.CSRFProtection(r)}});r.delegate(n.linkDisableSelector,"ajax:complete",function(){n.enableElement(e(this))});r.delegate(n.buttonDisableSelector,"ajax:complete",function(){n.enableFormElement(e(this))});r.delegate(n.linkClickSelector,"click.rails",function(r){var i=e(this),s=i.data("method"),o=i.data("params"),u=r.metaKey||r.ctrlKey;if(!n.allowAction(i))return n.stopEverything(r);if(!u&&i.is(n.linkDisableSelector))n.disableElement(i);if(i.data("remote")!==t){if(u&&(!s||s==="GET")&&!o){return true}var a=n.handleRemote(i);if(a===false){n.enableElement(i)}else{a.error(function(){n.enableElement(i)})}return false}else if(i.data("method")){n.handleMethod(i);return false}});r.delegate(n.buttonClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);if(r.is(n.buttonDisableSelector))n.disableFormElement(r);var i=n.handleRemote(r);if(i===false){n.enableFormElement(r)}else{i.error(function(){n.enableFormElement(r)})}return false});r.delegate(n.inputChangeSelector,"change.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);n.handleRemote(r);return false});r.delegate(n.formSubmitSelector,"submit.rails",function(r){var i=e(this),s=i.data("remote")!==t,o,u;if(!n.allowAction(i))return n.stopEverything(r);if(i.attr("novalidate")==t){o=n.blankInputs(i,n.requiredInputSelector);if(o&&n.fire(i,"ajax:aborted:required",[o])){return n.stopEverything(r)}}if(s){u=n.nonBlankInputs(i,n.fileInputSelector);if(u){setTimeout(function(){n.disableFormElements(i)},13);var a=n.fire(i,"ajax:aborted:file",[u]);if(!a){setTimeout(function(){n.enableFormElements(i)},13)}return a}n.handleRemote(i);return false}else{setTimeout(function(){n.disableFormElements(i)},13)}});r.delegate(n.formInputClickSelector,"click.rails",function(t){var r=e(this);if(!n.allowAction(r))return n.stopEverything(t);var i=r.attr("name"),s=i?{name:i,value:r.val()}:null;r.closest("form").data("ujs:submit-button",s)});r.delegate(n.formSubmitSelector,"ajax:send.rails",function(t){if(this==t.target)n.disableFormElements(e(this))});r.delegate(n.formSubmitSelector,"ajax:complete.rails",function(t){if(this==t.target)n.enableFormElements(e(this))});e(function(){n.refreshCSRFTokens()})}})(jQuery)
    jQuery(document).ready(function($){

        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                console.log('beforesend');
                settings.data += "&_token=<?php echo csrf_token() ?>";
            }
        });

        $.fn.editable.defaults.mode = 'inline';

        $('.editable').editable().on('hidden', function(e, reason){
            var locale = $(this).data('locale');
            if(reason === 'save'){
                $(this).removeClass('status-0').addClass('status-1');
            }
            if(reason === 'save' || reason === 'nochange') {
                var $next = $(this).closest('tr').next().find('.editable.locale-'+locale);
                setTimeout(function() {
                    $next.editable('show');
                }, 300);
            }
        });

        $(".editable").editable().on("shown", function(){
            var $next = $(this).next()
            var $btn_submit = $($next).find("button.editable-submit")
            var $btn_cancel = $($next).find("button.editable-cancel")

            $btn_submit.find("i").attr("class", "fa fa-check")
            $btn_cancel.find("i").attr("class", "fa fa-times")
        })

        $('#group').on('change', function(){
            var group = $(this).val();
            console.log(group)
            if (group) {
                window.location.href = '<?php echo action('\Barryvdh\TranslationManager\Controller@getView') ?>/'+$(this).val();
            } else {
                window.location.href = '<?php echo action('\Barryvdh\TranslationManager\Controller@getIndex') ?>';
            }
        });

        $("a.delete-key").on('confirm:complete',function(event,result){
            if(result)
            {
                var row = $(this).closest('tr');
                var url = $(this).attr('href');
                var id = row.attr('id');
                $.post( url, {id: id}, function(){
                    row.remove();
                } );
            }
            return false;
        });

        $('.form-import').on('ajax:success', function (e, data) {
            $('div.success-import strong.counter').text(data.counter);
            $('div.success-import').slideDown();
            window.location.reload();
        });

        $('.form-find').on('ajax:success', function (e, data) {
            $('div.success-find strong.counter').text(data.counter);
            $('div.success-find').slideDown();
            window.location.reload();
        });

        $('.form-publish').on('ajax:success', function (e, data) {
            $('div.success-publish').slideDown();
        });

        $('.form-publish-all').on('ajax:success', function (e, data) {
            $('div.success-publish-all').slideDown();
        });
        $('.enable-auto-translate-group').click(function (event) {
            event.preventDefault();
            $('.autotranslate-block-group').removeClass('hidden');
            $('.enable-auto-translate-group').addClass('hidden');
        })
        $('#base-locale').change(function (event) {
            console.log($(this).val());
            $.cookie('base_locale', $(this).val());
        })
        if (typeof $.cookie('base_locale') !== 'undefined') {
            $('#base-locale').val($.cookie('base_locale'));
        }

    })
</script>
@endsection
