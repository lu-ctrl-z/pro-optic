var $upSettings = {
    downsize: { w: 20, h: 30 },
    beforePreview: function() {
        $('.img-preview').prop( "src", '/img/dummy.png');
    },
    selector_preview: 'img[name="data[station_pic#i#]"]',
    input_name: 'tmpUploader[station_pic#i#]',
    browse_button: 'pickfiles'
};
var uploaderInit = function($S) {
    var uploader = new plupload.Uploader({
        downsize: $S.downsize || { w: 20, h: 30 },
        beforePreview: $S.beforePreview || function() {},
        initImagePre: $S.initImagePre || function() {},

        runtimes : 'html5,flash,silverlight,html4',
        browse_button : $S.browse_button,
        url : "/plug/plupload/upload.php",
        chunk_size : '1mb',
        tmpInput: 'tmpUploader',
        // User can upload no more then 6 files in one go (sets multiple_queues to false)
        max_file_count : 6,
        multiple_queues : false,
        unique_names : true,
        filters : {
            max_file_size : '10mb',
            mime_types : [ {
                title : "Image files",
                extensions : "jpg,gif,png,jpeg"
            }, ]
        },
        // Resize images on client-side if we can
        flash_swf_url : '/plug/plupload/js/Moxie.swf',
        silverlight_xap_url : '/plug/plupload/js/Moxie.xap',
        // PreInit events, bound before the internal events
        preinit : {
            Init : function(up, info) {
                console.log(up);
            }
        },
        init : {
            FilesAdded : function(up, files) {
            },
            FilesRemoved : function(up, files) {
            },
            UploadComplete : function(up, files) {
            },
        }
    });
    uploader.init();
};
