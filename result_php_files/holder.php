<!-- holds the code for search_donor_result.php -->
<?php
    if(isset($login)){
        require_once('../assets/php/connection.php'); //establishes connection to the database
?>
        <div class="container box">
            <div class="jumbotron">
                <div class="alert alert-success">
                    <div class="container" id="donor_container">
                        <h2>Search Results</h2>         
                        <table class="table table-striped" id="donor_table">
                            <thead>
                                <tr>
                                    <th class="csv_download">Donor ID</th>
                                    <th class="csv_download">Prefix</th>
                                    <th class="csv_download">First Name</th>
                                    <th class="csv_download">Last Name</th>
                                    <th class="csv_download">Suffix</th>
                                    <th class="csv_download">Entry Date</th>
                                    <th class="csv_download">PC Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div> <!-- closing div for table container -->
                        <?php
                            $pc_count = 0;
                            while($donor_row = mysqli_fetch_assoc($donor_result)){

                                $donor_id = $donor_row['donor_id'];
                                $prefix = $donor_row['prefix'];
                                $first_name = $donor_row['first_name'];
                                $last_name = $donor_row['last_name'];
                                $suffix = $donor_row['suffix'];
                                $entry_date = $donor_row['entry_date'];
                        ?>
                                <script>
                                    var table = document.getElementById("donor_table");
                                    var row = table.insertRow();

                                    //creates the cell for each donor attribute 
                                    var donor_id_cell = row.insertCell(0);
                                    var prefix_cell = row.insertCell(1);
                                    var first_name_cell = row.insertCell(2);
                                    var last_name_cell = row.insertCell(3);
                                    var suffix_cell = row.insertCell(4);
                                    var entry_date_cell = row.insertCell(5);
                                    
                                    //inserts value into each cell
                                    donor_id_cell.innerHTML = "<?php echo $donor_id ?>";
                                    prefix_cell.innerHTML = "<?php echo $prefix ?>";
                                    first_name_cell.innerHTML = "<?php echo $first_name ?>";
                                    last_name_cell.innerHTML = "<?php echo $last_name ?>";
                                    suffix_cell.innerHTML = "<?php echo $suffix ?>";
                                    entry_date_cell.innerHTML = "<?php echo $entry_date ?>";

                                    donor_id_cell.classList.add("csv_download");
                                    prefix_cell.classList.add("csv_download");
                                    first_name_cell.classList.add("csv_download");
                                    last_name_cell.classList.add("csv_download");
                                    suffix_cell.classList.add("csv_download");
                                    entry_date_cell.classList.add("csv_download");
                                </script>
                            <?php
                                $pc_query = "SELECT DISTINCT computer.pc_id FROM computer INNER JOIN donor ON computer.donor_id_f = '$donor_id'"; //builds the query to find matching pc_id
                                $pc_result = mysqli_query($mysql, $pc_query); 

                                //iterates through all matching PCs and inserts them into the appropriate cell
                                while($pc_row = mysqli_fetch_assoc($pc_result)){
                                    $pc_id = $pc_row['pc_id'];    
                                    $pc_count++;
                            ?>
                                    <script>
                                        var pc_id_cell = row.insertCell(6);
                                        var delete_cell = row.insertCell(7);

                                        //sets the delete button as a form to delete selected PC
                                        pc_id_cell.innerHTML = '<?php echo $pc_id ?>'; 
                                        pc_id_cell.classList.add("csv_download");

                                        delete_cell.innerHTML = '<form method="post" action="delete.php">'
                                        +'<button class="delete_button" type="submit" name="delete_button" id="<?php echo $pc_id ?>" >x</button> &nbsp'
                                        +'</form>';

                                        $('#<?php echo $pc_id ?>').val('<?php echo $pc_id ?>'); //insert PC value into the button
                                    </script>                               
                        <?php
                                }
                            }
                        ?>
                        <hr>
                        <h6>Total PC assigned: <?php echo $pc_count ?></h6>
                </div> <!-- closing div for alert success -->
                        <br>
                        <?php require_once('../assets/php/return_button.php'); ?>
                        <button class="btn btn-primary" onclick="exportTableToCSV('report.csv');">Export to CSV</button>     
            </div>  <!-- closing div for jumbotron -->
        </div> <!-- closing div for container box -->
    <script>
        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // CSV file
            csvFile = new Blob([csv], {type: "text/csv"});

            // Download link
            downloadLink = document.createElement("a");

            // File name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = "none";

            // Add the link to DOM
            document.body.appendChild(downloadLink);

            // Click download link
            downloadLink.click();
        }

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("table tr");
            
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll(".csv_download");
                
                for (var j = 0; j < cols.length; j++) 
                    row.push(cols[j].innerText);
                
                csv.push(row.join(","));        
            }

            downloadCSV(csv.join("\n"), filename); // Download CSV file
        }

    </script>
<?php
    }else{
        header("Location:../index_php_files/index.php");
    }
?>