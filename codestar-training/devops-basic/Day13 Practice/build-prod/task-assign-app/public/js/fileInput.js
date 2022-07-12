const handleFileInputChange = (files) => {
    if (files.length > 0) {
        let cnt = 0;
        resetTable('files');

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileSize = ((file.size / 1024) / 1024).toFixed(4); // MB
            
            if (fileSize <= 10) {
                const newLink = $('<a/>', {
                    href: URL.createObjectURL(file),
                    text: file.name,
                    target: '_blank'
                });
                addRowToTable('files', i, newLink);
                cnt++;
            }

        }

        if (cnt > 0) {
            $('#file-count span').html(cnt);
            $('#file-count').show();
        }
    }
    else {
        $('#file-count').hide();
    }
}

const handleFileCountClick = () => {
    $('#file-count').click(function() {
        $('#file-modal').modal('show');
    });
}