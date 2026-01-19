
<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset("theme/assets/plugins/global/plugins.bundle.js") }}"></script>
<script src="{{ asset("theme/assets/js/scripts.bundle.js") }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset("theme/assets/plugins/custom/fslightbox/fslightbox.bundle.js") }}"></script>
<script src="{{ asset("theme/assets/plugins/custom/datatables/datatables.bundle.js") }}"></script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{ asset("theme/assets/js/widgets.bundle.js") }}"></script>
<script src="{{ asset("theme/assets/js/custom/widgets.js") }}"></script>
<script src="{{ asset("theme/assets/js/custom/apps/chat/chat.js") }}"></script>
<script src="{{ asset("theme/assets/js/custom/utilities/modals/create-app.js") }}"></script>
<script src="{{ asset("theme/assets/js/custom/utilities/modals/users-search.js") }}"></script>
<script src="{{ asset("theme/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js") }}"></script>
<script>var HOST_URL = "{{ URL::to('/') }}";</script>
@include('layouts._scripts')
