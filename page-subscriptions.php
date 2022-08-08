<?php
    get_header();   
    set_include_path (get_template_directory());
    include_once('controllers/SubscriptionController.php');
    include_once('models/Subscription.php');

    include_once('controllers/UserController.php');
    include_once('models/User.php');

    include_once('controllers/PackageController.php');
    include_once('models/Package.php');

    $subscriptions = SubscriptionController::getAll();
    $users = UserController::getUsers();
    $packages = PackageController::getPackages();

    session_start();
    $errors = $_SESSION['error'] ?? [];
    $failedSubscription = $_SESSION['old_inputs'] ?? [];
    unset($_SESSION['error']);
    unset($_SESSION['old_inputs']);

    if($_SERVER['REQUEST_METHOD'] == 'GET') {
        if(isset($_GET['package'])) {
            $selectedPackage = PackageController::getPackageByID();
            echo '<script>
                        jQuery(document).ready(function() {
                            $("#packageModal").modal("show");
                        });
                    </script>
                 ';
        }
        if(isset($_GET['user'])){
            $selectedUser = UserController::getUserByID();
            $avatar = get_avatar_url($selectedUser->ID);
            echo '<script>
                    jQuery(document).ready(function($) {
                        $("#userModal").modal("show");
                    });
                </script>';
        }
        if(isset($_GET['notes'])){
            $selectedSubscriptionNotes = SubscriptionController::get();
            echo '<script>
                    jQuery(document).ready(function($) {
                        $("#notesModal").modal("show");
                    });
                </script>';
        }
        if(isset($_GET['subscription'])) {
            $selectedSubscription = SubscriptionController::get();
            echo '<script>
                    jQuery(document).ready(function($) {
                        $("#editSubscriptionModal").modal("show");
                    });
                </script>';
        }
        if(isset($_GET['add'])) {
            echo '<script>
                    jQuery(document).ready(function($) {
                        $("#addSubscriptionModal").modal("show");
                    });
                </script>';
        }
    }




    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['add'])){
            if(SubscriptionController::add()) {
                header('Location: ' . $_SERVER['PHP_SELF']. '/subscriptions');
            } else {
                header("Location:".$_SERVER['REQUEST_URI']);
            }
        }
        if(isset($_POST['remove'])){
            SubscriptionController::remove();
            header('Location: ' . $_SERVER['PHP_SELF']. '/subscriptions');
        }
        if(isset($_POST['update'])){
            if(SubscriptionController::update()) {
                header('Location: ' . $_SERVER['PHP_SELF']. '/subscriptions');
            } else {
                header("Location:".$_SERVER['REQUEST_URI']);
            }
        }

    }
?>
	<main id="primary" class="site-main">
        <!-- Modal -->
        <div class="modal fade" id="addSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Subscription</h5>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label" for="title">Title</label>
                                <input type="text" class="<?= isset($errors['title'])?  'form-control is-invalid': 'form-control is-valid' ?>" required id="title" name='title' placeholder="Title" value=<?= isset($failedSubscription['title'])? $failedSubscription['title']:'' ?>>
                                <?php 
                                    if(isset($errors['title'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['title'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="notes">Notes</label>
                                <textarea class="<?= isset($errors['notes'])?  'form-control is-invalid': 'form-control is-valid' ?>" name="notes" id="notes" rows="3"><?= isset($failedSubscription['notes'])? $failedSubscription['notes']:'' ?></textarea>
                                <?php 
                                    if(isset($errors['notes'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['notes'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option>Active</option>
                                    <option <?php echo isset($failedSubscription['status'])? $failedSubscription['status'] == 'Expired'?  'selected':'':''  ?>>Expired</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="user">User</label>
                                <select name='userID' class="<?= isset($errors['userID'])?  'form-control is-invalid': 'form-control is-valid' ?>" id="user">
                                    <option <?php echo isset($failedSubscription['userID'])? '': 'selected' ?> >Select User</option>
                                    <?php foreach($users as $user) { ?>
                                        <option <?php echo isset($failedSubscription['userID'])? $failedSubscription['userID']== $user->id? 'selected' : '':'' ?>  value="<?php echo $user->ID; ?>"><?php echo $user->user_nicename; ?></option>
                                    <?php } ?>
                                </select>
                                <?php 
                                    if(isset($errors['userID'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['userID'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="package">Package</label>
                                <select name='packageID' class="<?= isset($errors['packageID'])?  'form-control is-invalid': 'form-control is-valid' ?>" id="package">
                                    <option <?php echo isset($failedSubscription['packageID'])? '': 'selected' ?>>Select Package</option>
                                    <?php foreach($packages as $package) { ?>
                                        <option <?php echo isset($failedSubscription['packageID'])? $failedSubscription['packageID']== $package->id? 'selected' : '':'' ?> value="<?php echo $package->id; ?>"><?php echo $package->title; ?></option>
                                    <?php } ?>
                                </select>
                                <?php 
                                    if(isset($errors['packageID'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['packageID'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="package_count">Package Count</label>
                                <input name='package_count' type="text" class="<?= isset($errors['package_count'])?  'form-control is-invalid': 'form-control is-valid' ?>" id="package_count" placeholder="Package Count" value="<?=isset($failedSubscription['package_count'])? $failedSubscription['package_count']:'' ?>">
                                <?php 
                                    if(isset($errors['package_count'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['package_count'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <!-- expires at -->
                                <label for="expires_at">Expires At</label>
                                <input name='expires_at' type="date" class="<?= isset($errors['expires_at'])?  'form-control is-invalid': 'form-control is-valid' ?>" id="expires_at" placeholder="Expires At" value="<?=isset($failedSubscription['expires_at'])? $failedSubscription['expires_at']:'' ?>">
                                <?php 
                                    if(isset($errors['expires_at'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['expires_at'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onClick="closeAddSubscriptionModal()" data-dismiss="modal">Close</button>
                            <button type="submit" name="add" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <!-- edit subscription modal -->
        <div class="modal fade" id="editSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Subscription</h5>
                    </div>
                    <form action="" method="POST">
                        <!-- index -->
                        <input type="hidden" name="index" id="index" value=<?= $selectedSubscription->id ?>>
                        <!-- created at -->
                        <input type="hidden" name="created_at" id="created_at" value=<?= $selectedSubscription->created_at ?>>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label" for="title">Title</label>
                                <input type="text" class="<?= isset($errors['title'])?  'form-control is-invalid': 'form-control is-valid' ?>" required id="title" name='title' placeholder="Title" value=<?= $selectedSubscription->title ?>>
                                <?php 
                                    if(isset($errors['title'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['title'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="notes">Notes</label>
                                <textarea class="<?= isset($errors['notes'])?  'form-control is-invalid': 'form-control is-valid' ?>" name="notes" id="notes" rows="3"><?= $selectedSubscription->notes ?></textarea>
                                <?php 
                                    if(isset($errors['notes'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['notes'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option <?= $selectedSubscription->status=='Active'? 'selected':Null ?> >Active</option>
                                    <option <?= $selectedSubscription->status=='Expired'? 'selected':Null ?> >Expired</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="user">User</label>
                                <select name='userID' class="form-control" id="user">
                                    <option value=<?= $selectedSubscription->user->id ?> selected><?= $selectedSubscription->user->user_nicename ?></option>
                                    <?php foreach($users as $user) { 
                                        if($user->id != $selectedSubscription->user->id) {
                                        ?>
                                        <option  value="<?php echo $user->ID; ?>"><?php echo $user->user_nicename; ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="package">Package</label>
                                <select name='packageID' class="form-control" id="package">
                                    <option value=<?= $selectedSubscription->package->id ?> selected><?= $selectedSubscription->package->title ?></option>
                                    <?php foreach($packages as $package) { 
                                        if($package->id != $selectedSubscription->package->id) {
                                        ?>
                                        <option value="<?php echo $package->id; ?>"><?php echo $package->title; ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="package_count">Package Count</label>
                                <input name='package_count' type="text" class="<?= isset($errors['package_count'])?  'form-control is-invalid': 'form-control is-valid' ?>" id="package_count" placeholder="Package Count" value=<?= $selectedSubscription->package_count ?>>
                                <?php 
                                    if(isset($errors['package_count'])) {
                                        echo '<div class="text-danger"> <small>'.$errors['package_count'].'</small></div>';
                                    } else {
                                        echo '<div class="text-success"> <small>Looks good!</small></div>';
                                    }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="expires_at">Expires At</label>
                                <input name="expires_at"  type="date" class="form-control" id="expires_at" placeholder="Expires At" value=<?= $selectedSubscription->expires_at ?>>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onClick="closeEditSubscriptionModal()" data-dismiss="modal">Close</button>
                            <button type="submit" name="update" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        

        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><?= $selectedUser->user_nicename ?></h5>
                </div>
                <div class="modal-body">
                    <div class="card" style="width: 18rem;">
                        <?= $avatar?  ("<img class='card-img-top' src=$avatar alt='Card image cap'>")   : ''  ?>
                        <div class="card-body">
                            <p class="card-text" id='user_email'><?= $selectedUser->user_email ?> </p>
                            <label for="user_registered">Created At</label>
                            <p class="card-text" id='user_registered'><?= ' '. $selectedUser->user_registered ?></p>
                            <p class="card-text" id='role'><?= $selectedUser->roles[0] ?></p>
                            <!-- biographical info user -->
                            <label for="description">Description</label>
                            <p class="card-text" id='description'><?= $selectedUser->description ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onClick="closeUserModal()" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Notes</h5>
                </div>
                <div class="modal-body">
                    <?php
                        echo "<p class='text-break'> $selectedSubscriptionNotes->notes </p>";
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onClick="closeNotesModal()" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="packageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Package</h5>
                </div>
                <div class="modal-body">
                    <!-- $selectedPackage->title -->
                    <!-- $selectedPackage->notes -->
                    <!-- $selectedPackage->days -->
                    <!-- $selectedPackage->price -->

                    <div class="card" style="width: 18rem;">
                        
                        <div class="card-body">
                            <p class="card-text" id='package_title'><?= $selectedPackage->title ?> </p>
                            <label for="package_notes">Notes</label>
                            <p class="card-text" id='package_notes'><?= $selectedPackage->notes ?></p>
                            <label for="package_days">Days</label>
                            <p class="card-text" id='package_days'><?= $selectedPackage->days ?></p>
                            <label for="package_price">Price</label>
                            <p class="card-text" id='package_price'><?= $selectedPackage->price ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onClick="closePackageModal()" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>



        <div id="block">
            <div class="container">
                <div class="tbodyDiv">
                    <table class="table table-responsive  table-hover table-bordered table-striped ">
                        <thead class="sticky-top bg-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">User</th>
                                <th scope="col">Package</th>
                                <th scope="col">Count</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Expires At</th>
                                <th scope="col">Updated At</th>
                                <th scope="col">Status</th>
                                <th scope="col">Notes</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php 

                                foreach($subscriptions as $subscription) {
                            ?>
                            <tr class= "">
                                    <td>
                                        <div>
                                            <?=  $subscription->id ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?=  $subscription->title ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <form action="" method="GET">
                                                <input type="hidden" name="index" value="<?= $subscription->user->ID ?>">
                                                <button type="submit" name="user" class="btn btn-link">
                                                    <?php 
                                                        $img = get_avatar_url($subscription->user->ID);
                                                        echo("<img src=$img alt='Card image cap' width='44' height='44'>");
                                                    ?>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <form action="" method="GET">
                                                <input type="hidden" name="index" value="<?= $subscription->package->id ?>">
                                                <button type="submit" name="package" class="btn btn-link">
                                                    <?= $subscription->package->title?>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?=  $subscription->package_count ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?=  $subscription->created_at ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?=  $subscription->expires_at ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?=  $subscription->last_updated_at ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?=  $subscription->status ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?php 
                                                $subscription->notes? 
                                                    print("
                                                            <form action='' method='GET'>
                                                                <input type='hidden' name='index' value='".$subscription->id."'>
                                                                <button type='submit' name='notes' class='btn btn-link'>
                                                                    Show notes
                                                                </button>
                                                            </form>
                                                        "):
                                                    print("No notes")
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between  ">
                                            <form action="" method="POST">
                                                <input type="hidden" name="index" value="<?php echo $subscription->id; ?>"/>
                                                <button type="submit" name="remove" class="btn btn-danger">
                                                    <div>
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </div>
                                                </button>
                                            </form>
                                            <form action="" method="GET">
                                                <input type="hidden" name="index" value="<?php echo $subscription->id; ?>"/>
                                                <button type="submit" name="subscription" class="btn btn-warning">
                                                    <ion-icon name="create-outline"></ion-icon>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between  ">
                    <div class="p-2">
                        <p class='fw-bold'>Subscriptions list</p>
                    </div>
                    <form action="" method="GET">
                        <button type="submit" name="add" class = 'btn btn-success' id="addButton">
                            <ion-icon name="add-outline"></ion-icon>
                        </button>
                    </form>
                </div>
            </div>
        </div>
	</main>

    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        function openAddSubscriptionModal() {
            jQuery(document).ready(function($) {
                $('#addSubscriptionModal').modal('show');
            });
        }
        function closeAddSubscriptionModal() {
            jQuery(document).ready(function($) {
                $('#addSubscriptionModal').modal('hide');
            });
        }
        function closeUserModal() {
            jQuery(document).ready(function($) {
                $('#userModal').modal('hide');
            });
        }
        function closeNotesModal() {
            jQuery(document).ready(function($) {
                $('#notesModal').modal('hide');
            });
        }
        function closePackageModal() {
            jQuery(document).ready(function($) {
                $('#packageModal').modal('hide');
            });
        }
        function closeEditSubscriptionModal() {
            jQuery(document).ready(function($) {
                $('#editSubscriptionModal').modal('hide');
            });
        }
    </script>
<?php
// get_sidebar();
// get_footer();