import './bootstrap';
import 'bootstrap/dist/js/bootstrap.min.js';

const dropzoneAreas = document.querySelectorAll('.dropzone-area');


if( dropzoneAreas.length > 0 ){
    dropzoneAreas.forEach( dropzoneArea => {

        dropzoneArea.addEventListener('change', (e) => {


            let file = e.target.files[0];
            let fileNameWrapper = dropzoneArea.querySelector('.file-name');

            if( file ){
                let fileName = file.name
                let fileSize = humanReadableBytes(file.size)

                fileNameWrapper.innerHTML = `${fileName} (${fileSize})`;
            } else {
                fileNameWrapper.innerHTML = '<i class="bi bi-upload"></i> Upload a file';
            }




        });
    });
}

function humanReadableBytes(bytes) {
    let i = Math.floor( Math.log(bytes) / Math.log(1024) );
    return ( bytes / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
}