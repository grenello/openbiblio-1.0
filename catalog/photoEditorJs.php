<script language="JavaScript" >
<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
   See the file COPYRIGHT.html for more details.
 */
?>
// JavaScript Document
"use strict";

var wc = {
	init: function () {
		wc.url = '../catalog/catalogServer.php';
		$('.help').hide();

		wc.showFotos = false;
        <?php if ($_SESSION['show_item_photos'] == 'Y') { ?>
		wc.showFotos = true;
        <?php } ?>

		if (wc.showFotos) {
			wc.canvasOut = document.getElementById('canvasOut'),
			wc.ctxOut = canvasOut.getContext('2d');

	        //LJ: Not shown in .php equivalent for this situation, so will cause a exception.
			wc.canvasIn = document.getElementById('canvasIn'),
			wc.ctxIn = canvasIn.getContext('2d');
        }

//		wc.initWidgets();

		$('input.fotoSrceBtns').on('click',null,wc.changeImgSource);
		$('#capture').on('click',null,wc.takeFoto);
		$('#browse').on('change',null,wc.getFotoFile);
		$('#addFotoBtn').on('click',null,wc.sendFoto);
		$('#updtFotoBtn').on('click',null,wc.doUpdatePhoto);
		$('#deltFotoBtn').on('click',null,wc.doDeletePhoto);

		/* support drag and drop of image */
		wc.canvasOut.ondragover = function (e){
			// keep browser from replacing entire page with dropped image //
            e.preventDefault();
			return false;
		};
		wc.canvasOut.ondrop = function (e) {
			wc.getFotoDrop(e);
		};
		/* support cut&paste of image */
		document.onpaste = function (e) {
			wc.getFotoPaste(e);
		};

		wc.resetForm();

	},
	//------------------------------
	initWidgets: function () {
console.log('in wc::initWidgets()');
		if (wc.showFotos) {
			wc.fotoWidth = <?php echo Settings::get('thumbnail_width');?> || 176;
			wc.fotoHeight = <?php echo Settings::get('thumbnail_height');?> || 233;
			wc.fotoRotate = <?php echo Settings::get('thumbnail_rotation');?> || 0;
			wc.cameraId = "<?php echo Settings::get('camera');?>";
		}

	    wc.video = document.querySelector('video');

/*
      	//no longer valid? - FL May2017
		navigator.getUserMedia = navigator.getUserMedia
							  || navigator.webkitGetUserMedia
                              || navigator.mozGetUserMedia
							  || navigator.msGetUserMedia
                              || navigator.oGetUserMedia
        ;
		console.log(mediaDevices.enumerateDevices);

		if (navigator.getUserMedia) {
		    navigator.getUserMedia({
                video:true,
                audio:false
            },
            function (stream) {
	            wc.video = document.querySelector('video');
   	            wc.video.src = window.URL.createObjectURL(stream);
                wc.localstream = stream;
                wc.video.play();
                //console.log("streaming");
            },
            function (error) {
 			    alert("<?php echo T("allowWebcamAccess4Camera"); ?>");
			    console.log("Video capture error: ", error.code);
            });

		}
*/
/*
		// this is Mozilla recommended solution, doesn't seem to work here - FL May2017
		var constraints = {
			audio: false,
			video: { width: {min: wc.fotoWidth},
					 height:{min: wc.fotoHeight}
				   }
		};

		console.log(navigator.mediaDevices.enumerateDevices());

		navigator.mediaDevices.getUserMedia(constraints)
			.then(function(mediaStream) {
			  	video = document.querySelector('video');
			  	video.srcObject = mediaStream;
			  	video.onloadedmetadata = function(e) {
			    	video.play();
			  	};
			})
			.catch(function(err) {      // always check for errors at the end.
				console.log('fotoErr: '+err.name + ": " + err.message);
			});
*/
/*
		// here is another method that might allow choice of cameras - FL Jun2017
        //----------------------------------------------------------------------
        //  Here we list all media devices, in order to choose between
        //  the front and the back camera.
        //      videoDevices[0] : Front Camera
        //      videoDevices[1] : Back Camera
        //  I used an array to save the devices ID
        //  which i get using devices.forEach()
        //  Then set the video resolution.
        //----------------------------------------------------------------------
		navigator.mediaDevices.enumerateDevices()
	    .then(devices => {
			wc.videoDevices = [0,0];
			var videoDeviceIndex = 0;
			devices.forEach(function(device) {
				if (device.kind == "videoinput") {
//					console.log(device.kind + ": " + device.label + " id = " + device.deviceId);
				  	wc.videoDevices[videoDeviceIndex++] =  device.deviceId;
				}
			});

			var constraints =  {
				video:{width: { min: wc.fotoWidth },
					   height: { min: wc.fotoHeight },
					   deviceId: { exact: wc.videoDevices[2]},
					  },
				audio: false,
				};
	        return navigator.mediaDevices.getUserMedia(constraints);
	    })
        .then(stream => {
			if (window.webkitURL) {
				wc.video.src = window.webkitURL.createObjectURL(stream);
				wc.localMediaStream = stream;
			} else if (wc.video.mozSrcObject !== undefined) {
				wc.video.mozSrcObject = stream;
			} else if (wc.video.srcObject !== undefined) {
				wc.video.srcObject = stream;
			} else {
				wc.video.src = stream;
          	}
		})
        .catch(e => console.error(e));
*/
		// Final version?

		const constraints =  {
			video:{width: { min: wc.fotoWidth },
				   height: { min: wc.fotoHeight },
				   deviceId: { exact: wc.cameraId},
				  },
			audio: false,
			};
	    navigator.mediaDevices.getUserMedia(constraints);



		list.getMediaList()
        .then(stream => {

			if (window.webkitURL) {
				wc.video.src = window.webkitURL.createObjectURL(stream);
				wc.localMediaStream = stream;
			} else if (wc.video.mozSrcObject !== undefined) {
				wc.video.mozSrcObject = stream;
			} else if (wc.video.srcObject !== undefined) {
				wc.video.srcObject = stream;
			} else {
				wc.video.src = stream;
          	}
		})
        .catch(e => console.error(e));
	},

    vidOff: function () {
        //clearInterval(theDrawLoop);
        //ExtensionData.vidStatus = 'off';
        wc.video.pause();
        wc.video.src = "";
        wc.localstream.getTracks()[0].stop();
        console.log("Vid off");
    },

	//----//
	resetForm: function () {
		$('.help').hide();
        $('#fotoDiv').hide();
		$('#camera').hide();
		$('#canvasIn').hide();
		$('#fotoAreaDiv').show();
		$('#addFotoBtn').show();
		wc.changeImgSource();
	},

	//----//
	changeImgSource: function (e) {
		var chkd = $('input[name=imgSrce]:checked', '#fotoForm').val();
		if (chkd == 'cam') {
			$('#camera').attr('autoplay',true);
			//$('#fotoName').val('filename.jpg');
			$('#capture').show();
			$('#browse').hide();
		} else if (chkd == 'brw') {
			$('#camera').removeAttr('autoplay');
			//$('#fotoName').val('');
			$('#capture').hide();
			$('#browse').show();
		}
	},

	//----//
	rotateImage: function (angle) {
        var tw = wc.canvasIn.width/2,
    		th = wc.canvasIn.height/2,
    		angle = angle*(Math.PI/180.0);
		wc.ctxIn.save();
		wc.ctxIn.translate(tw, th);
		wc.ctxIn.rotate(angle);
		wc.ctxIn.translate(-th, -tw);
		wc.ctxIn.drawImage(canvasIn, 0,0);
		wc.ctxIn.restore();
	},
	showImage: function (fn) {
		var img = new Image;
		img.onload = function() { wc.ctxOut.drawImage(img, 0,0, wc.fotoWidth,wc.fotoHeight); };
		img.src = fn;
	},
	eraseImage: function () {
		wc.ctxOut.clearRect(0,0, wc.canvasOut.width,wc.canvasOut.height)
		wc.ctxIn.clearRect(0,0, wc.canvasIn.width,wc.canvasIn.height)
	},

	//------------------------------
	getFotoPaste: function (e) {
		e.preventDefault();
		if (e.clipboardData &&e.clipboardData.items) {
			var items = e.clipboardData.items;
			for (var i=0; i<items.length; i++) {
				if (items[i].kind === 'file' && items[i].type.match(/^image/)) {
					wc.readFile(items[i].getAsFile());
					break;
				}
			}
		}
		return false;
	},
	getFotoDrop: function (e) {
		e.preventDefault();
		e = e || window.event;
		var files = e.dataTransfer.files;
		if (files) wc.readFile(files[0]);
	},
	getFotoFile: function (e) {
		// Get the FileList object from the file select event
		var files = e.target.files;
		if(files.length === 0) return;
		var file = files[0];
		if(file.type !== '' && !file.type.match('image.*')) return;
		//$('#fotoName').val($('#browse').val());
		wc.readFile(file)
	},
	readFile: function (file) {
		var reader = new FileReader();  // output is to reader.result
		reader.onerror = function () {
			console.log('FileReader error: '+reader.error);
			return;
		}
		reader.onloadend = function(e) {
        	var tempImg = new Image();
        	tempImg.src = reader.result;
        	tempImg.onload = function() {
                wc.ctxOut.drawImage(tempImg, 0, 0, wc.fotoWidth,wc.fotoHeight);
    		}
		};
        reader.readAsDataURL(file);
	},
	takeFoto: function () {
  	    $('#errSpace').hide();
		wc.ctxIn.drawImage(wc.video,0,0, wc.fotoHeight,wc.fotoWidth);
		wc.rotateImage(wc.fotoRotate);
		wc.ctxOut.drawImage(wc.canvasIn,0,0, wc.fotoWidth,wc.fotoHeight, 0,0, wc.fotoWidth,wc.fotoHeight);
	},
	sendFoto: function (e) {
		//console.log('sending foto');
		if (e) e.stopPropagation();
//		obib.hideMsg(); // clear any user msgs
		var imgMode = '',
			current_add_mode = '',
			url = $('#fotoName').val(),
			bibid = $('#fotoBibid').val();
		imgMode = (url.substr(-3) == 'png')? 'image/png' : 'image/jpeg';
		current_add_mode = $('.fotoSrceBtns:checked').val();
		if ((current_add_mode == 'brw') || (current_add_mode == 'cam')) {
			$.post(wc.url,
				{'mode':'addNewPhoto',
			 	 'type':'base64',
			 	 'img':wc.canvasOut.toDataURL(imgMode, 0.8),
                 'bibid':bibid,
			 	 'url': url,
                 'position':0,
				},
				function (data) {
					//console.log('local image posting OK');
					var crntFotoUrl = data[0]['url'];
					// paste this photo to parent biblio (used by 'Existing Item' viewer)
					$('#bibBlkB').html('<img src="'+crntFotoUrl+'" id="biblioFoto" class="hover" '
						+ 'height="'+wc.fotoHeight+'" width="'+wc.fotoWidth+'" >');
					if (typeof bs !== 'undefined') {
						bs.crntFotoUrl = crntFotoUrl;
						bs.getPhoto(bibid, '#photo_'+bibid );
					}
					obib.showMsg('<?php echo T("cover photo posted");?>: '+crntFotoUrl);
					$('#photoAddBtn').hide();
					$('#photoEditBtn').show();
				}, 'json'
			);
		//e.preventDefault();
		} else {
			$.post(wc.url,
				{'mode':'addNewRemotePhoto',
               			'bibid':bibid,
			 	'url': url,
				},
				function (data) {
					//console.log('remote image posting OK');
					var crntFotoUrl = data[0]['url'];
					$('#bibBlkB').html('<img src="'+crntFotoUrl+'" id="biblioFoto" class="hover" '
							+ 'height="'+wc.fotoHeight+'" width="'+wc.fotoWidth+'" >');
					if (typeof bs !== 'undefined') {
						bs.crntFotoUrl = crntFotoUrl;
						bs.getPhoto(bibid, '#photo_'+bibid );
					}
					obib.showMsg('<?php echo T("cover photo posted");?>');
					$('#photoAddBtn').hide();
					$('#photoEditBtn').show();
				}, 'json'
			);
		}
		obib.showMsg('<?php echo T("cover photo posted");?>');
//		return false;
	},

	doUpdatePhoto: function (e) {
        var callFinishUpdate = true;
        wc.deleteActual(e , function(e) {wc.callFinishUpdte(e)}); // returns before actual work done by server
	},
    finishUpdate: function (e) {
		//console.log('attempting update');
        wc.sendFoto(e);
    },

	doDeletePhoto: function (e) {
		if (confirm("<?php echo T("Are you sure you want to delete this cover photo"); ?>")) {wc.deleteActual(e); }
    },
    deleteActual: function (e, forUpdate=false) {
  	    $.post(wc.url,{'mode':'deletePhoto',
				       'bibid':$('#fotoBibid').val(),
					   'url':$('#fotoName').val(),
	              },
				  function(response){
						//console.log('back from deleting');
						wc.eraseImage();
						$('#bibBlkB').html('<img src="../images/shim.gif" id="biblioFoto" class="noHover" '
  											+ 'height="'+wc.fotoHeight+'" width="'+wc.fotoWidth+'" >');
                        idis.crntFoto = null;
						$('#photoAddBtn').show();
						$('#photoEditBtn').hide();

                        if(forUpdate) {
                            wc.finishUpdate();
                        } else {
					       $('#fotoName').val('');
						   obib.showMsg('<?php echo T("cover photo deleted");?>');
                       }
				 }
                 , 'json'
		);
		e.stopPropagation();
		return false;
	},

}
/*  this code should be explicity initialized when needed unless
	the frequent appearance of the video allow/deny prompt is acceptable

    in Mozilla Firefox 46+, you can elinate the prompt to allow video capture
    at "about:config | media.navigator.permission.disabled"
    BUT BE WARY, once on, the camera is accesable by any page in the browser until OB is turned off

 */
<?php if ($_SESSION['show_item_photos'] == 'Y') { ?>
  $(document).ready(wc.init);
<?php } ?>

</script>
