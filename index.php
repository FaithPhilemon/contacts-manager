<?php
    include_once "includes/lib.php";
    check_authentication();

    $categories = get_categories();
    $contacts = list_contacts($_SESSION['userId']);

  // initiate variables to be used for error and success messages
  $loginSuccess = $newContactSuccess =  $contactUpdateMessage = $deleteContactMessage = "";

  // checking for incoming query string requests
  if(isset($_GET['login'])){
    if($_GET['login'] == 'success'){
      $loginSuccess = 'You have been logged in successfully';
    }
  }


  if(isset($_GET['success'])){
    if($_GET['success'] == 'newContact'){
      $newContactSuccess = 'New contact added successfully';
    }
  }


  if(isset($_GET['contact'])){
    if($_GET['contact'] == 'updated'){
      $contactUpdateMessage = 'Contact updated successfully';
    }
  }


  if(isset($_GET['contact'])){
    if($_GET['contact'] == 'deleted'){
      $deleteContactMessage = 'Contact deleted successfully';
    }
  }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTACTS MANAGER</title>
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="<?= $appUrl ?>assets/css/bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css" integrity="sha512-pVCM5+SN2+qwj36KonHToF2p1oIvoU3bsqxphdOIWMYmgr4ZqD3t5DjKvvetKhXGc/ZG5REYTT6ltKfExEei/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css" integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
    <link rel="stylesheet" href="<?= $appUrl ?>assets/css/index.css">

    <script src="<?= $appUrl ?>assets/js/bootstrap5.min.js"></script>
    
</head>
<body>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <h5 class="card-title">Contact List <span class="text-muted fw-normal ms-2">( <?= get_num_rows($_SESSION['userId']) ?> )</span></h5>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                    <div>
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a
                                    aria-current="page"
                                    href="#"
                                    class="router-link-active router-link-exact-active nav-link active"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title=""
                                    data-bs-original-title="List"
                                    aria-label="List"
                                >
                                    <i class="bx bx-list-ul"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Grid" aria-label="Grid"><i class="bx bx-grid-alt"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalId">Add New</button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#emailModal">Send Email</button>
                    </div>
                    <div class="dropdown">
                        <a class="btn btn-link text-muted py-1 font-size-16 shadow-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-horizontal-rounded"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">


            <div class="col-lg-12">
                <?php if($newContactSuccess != ""): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Success!</strong> your new contact has been saved.
                    </div>
                <?php endif; ?>

                <?php if($contactUpdateMessage != ""): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Contact</strong> has been updated successfully!
                    </div>
                <?php endif; ?>


                <?php if($deleteContactMessage != ""): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Contact</strong> has been deleted successfully!
                    </div>
                <?php endif; ?>


                <div class="">
                    <div class="table-responsive">
                        <table class="table project-list-table table-nowrap align-middle table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col" class="ps-4" style="width: 50px;">
                                        <div class="form-check font-size-16"><input type="checkbox" class="form-check-input" id="contacusercheck" /><label class="form-check-label" for="contacusercheck"></label></div>
                                    </th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Address</th>
                                    <th scope="col" style="width: 200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $contacts = list_contacts($_SESSION['userId']); ?>

                                <?php foreach($contacts as $contact): ?>
                                        <tr>
                                            <th scope="row" class="ps-4">
                                                <div class="form-check font-size-16"><input type="checkbox" class="form-check-input" id="contacusercheck1" /><label class="form-check-label" for="contacusercheck1"></label></div>
                                            </th>
                                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" /><a href="#" class="text-body"> <?= $contact['first_name'] .' '.$contact['last_name'] ?></a></td>
                                            <td><span class="badge badge-soft-success mb-0"><?= get_category($contact['category_id']) ?></span></td>
                                            <td><?= $contact['email'] ?></td>
                                            <td><?= $contact['address'] ?></td>
                                            <td>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item">
                                                        <a type="button" class="px-2 text-primary" data-bs-toggle="modal" data-bs-target="#modalId<?=$contact['id']?>"><i class="bx bx-pencil font-size-18"></i></a>

                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a type="button" class="px-2 text-danger" data-bs-toggle="modal" data-bs-target="#deleteModalId<?=$contact['id']?>"><i class="bx bx-trash-alt font-size-18"></i></a>
                                                    </li>
                                                    
                                                </ul>
                                            </td>
                                        </tr>



                                        <div class="modal fade" id="modalId<?=$contact['id']?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTitleId">Edit contact</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="includes/lib.php" method="POST">
                                                            <input type="hidden" value="<?=$contact['id']?>" name="id">
                                                            <div class="mb-3">
                                                                <input type="text" value="<?=$contact['first_name']?>" class="form-control" name="first_name" placeholder="First Name" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <input type="text" value="<?=$contact['last_name']?>" class="form-control" name="last_name" placeholder="Last Name">
                                                            </div>
                                                            <div class="mb-3">
                                                                <input type="email" value="<?=$contact['email']?>" class="form-control" name="email" placeholder="Email" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <input type="text" value="<?=$contact['address']?>" class="form-control" name="address" placeholder="Address">
                                                            </div>
                                                            <div class="mb-3">
                                                                <select class="form-select" name="category" required>
                                                                    <option value="">Select Category</option>
                                                                    <?php foreach ($categories as $category) : ?>
                                                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <button type="submit" name="btnUpdateContact" class="btn btn-primary">Update Contact</button>

                                                            
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        

                                        <div class="modal fade" id="deleteModalId<?=$contact['id']?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTitleId">Are you sure you want to delete <span class="text-danger"><?=$contact['first_name']?>?</span></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="includes/lib.php" method="POST">
                                                            <input type="hidden" value="<?=$contact['id']?>" name="id">
                                                            <button type="submit" name="btnDeleteContact" class="btn btn-primary">YES, DELETE</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">NO, DONT</button>

                                                            
                                                        </form>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                                
                                <?php endforeach; ?>
                                                                                           
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-0 align-items-center pb-4">
            <div class="col-sm-6">
                <div><p class="mb-sm-0">Showing 1 to 10 of 57 entries</p></div>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-end">
                    <ul class="pagination mb-sm-0">
                        <li class="page-item disabled">
                            <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a href="#" class="page-link">1</a></li>
                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                        <li class="page-item"><a href="#" class="page-link">3</a></li>
                        <li class="page-item"><a href="#" class="page-link">4</a></li>
                        <li class="page-item"><a href="#" class="page-link">5</a></li>
                        <li class="page-item">
                            <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Add new contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="includes/lib.php" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="address" placeholder="Address">
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="category">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="btnSubmitContact" class="btn btn-primary">Save Contact</button>

                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="emailModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Send an Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="includes/lib.php" method="POST">
                    <div class="mb-3">
                        <select class="form-select" name="reciepient" required>
                            <option value="">Select Reciepient</option>
                            <option value="0">Send to all</option>
                            <?php foreach ($contacts as $contact) : ?>
                                <option value="<?php echo $contact['email']; ?>"><?php echo $contact['email']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Subject</label>
                        <input type="text" class="form-control" name="subject" placeholder="Email Subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Message</label>
                        <textarea name="message" class="form-control" cols="30" rows="10" required>

                        </textarea>
                    </div>
                   
                    <button type="submit" name="btnSendEmail" class="btn btn-primary">SEND</button>

                    
                </form>
            </div>
            
        </div>
    </div>
</div>


<!-- Optional: Place to the bottom of scripts -->
<script>
    const myModal = new bootstrap.Modal(document.getElementById('modalId'), options)

</script>


<script>
  var alertList = document.querySelectorAll('.alert');
  alertList.forEach(function (alert) {
    new bootstrap.Alert(alert)
  })
</script>





