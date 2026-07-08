<!-- CONTENT WRAPPER -->
<div class="ec-content-wrapper">
    <div class="content">
        <div class="breadcrumb-wrapper breadcrumb-contacts">
            <div>
                <h1>Bulk Product Upload</h1>
                <p class="breadcrumbs"><span><a href="#">Home</a></span>
                    <span><i class="mdi mdi-chevron-right"></i></span>Bulk Product Upload 
                </p>
            </div>
            <div>
                <a href="<?php echo base_url('assets/frontend/product_catalog.csv'); ?>" class="btn btn-primary">Bulk CSV Sample Download</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="ec-cat-list card card-default">
                    <div class="card-body">
                     <!-- Success and Error Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <p style="color: green;"><?php echo session()->getFlashdata('success'); ?></p>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color: red;"><?php echo session()->getFlashdata('error'); ?></p>
    <?php endif; ?>

    <?php if(session()->getFlashdata('errors')): ?>
        <ul>
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <li style="color: red;"><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?php echo base_url('admin/uploadCSV'); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Drag and Drop area -->
        <div id="drop-area" class="drop-area">
            <p>Drag and drop your CSV file here or <span class="upload-text">browse</span> to select a file</p>
            <input type="file" name="file" id="fileInput" class="file-input" accept=".csv" />
        </div>

        <div id="file-name" class="file-name"></div>

        <button type="submit">Upload CSV</button>
    </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Content -->
</div> <!-- End Content Wrapper -->

  <!-- Add some styling for the drag-and-drop area -->
    <style>
        .drop-area {
            border: 2px dashed #007bff;
            padding: 30px;
            text-align: center;
            font-size: 16px;
            color: #555;
            border-radius: 10px;
            cursor: pointer;
        }

        .drop-area.hover {
            background-color: #f0f8ff;
        }

        .upload-text {
            color: #007bff;
            text-decoration: underline;
        }

        .file-name {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>

    <!-- Add JavaScript for handling drag and drop functionality -->
    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('fileInput');
        const fileNameDisplay = document.getElementById('file-name');

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('hover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('hover');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('hover');

            const file = e.dataTransfer.files[0];
            if (file && file.type === 'application/vnd.ms-excel') { 
                fileNameDisplay.textContent = `Selected file: ${file.name}`;
                fileInput.files = e.dataTransfer.files;
            } else {
                alert("Please upload a valid CSV file.");
            }
        });

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file && file.type === 'application/vnd.ms-excel') {
                fileNameDisplay.textContent = `Selected file: ${file.name}`;
            } else {
                alert("Please upload a valid CSV file.");
            }
        });

        dropArea.addEventListener('click', () => {
            fileInput.click();
        });
    </script>
