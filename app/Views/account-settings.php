<?php
$role = session()->get('role');

$layouts = [
    'user' => 'layouts/user-base',
    'admin' => 'layouts/admin-base',
    'records' => 'layouts/records-base',
    'unit' => 'layouts/unit-base',
    'division' => 'layouts/division-base',
    'penro' => 'layouts/organization-base'
];

$layout = $layouts[$role];
?>
<?= $this->extend($layout) ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-gear me-2"></i>Account Settings</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= esc(session()->getFlashdata('success')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= esc(session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <!-- Accordion for Account Settings Sections -->
                    <div class="accordion" id="accountSettingsAccordion">
                        <!-- Account Info Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="accountInfoHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accountInfoCollapse" aria-expanded="true" aria-controls="accountInfoCollapse">
                                    <i class="bi bi-person-circle me-2"></i> Account Information
                                </button>
                            </h2>
                            <div id="accountInfoCollapse" class="accordion-collapse collapse show" aria-labelledby="accountInfoHeading" data-bs-parent="#accountSettingsAccordion">
                                <div class="accordion-body">
                                    <div class="row">

                                        <!-- LEFT: Profile Picture -->
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold">Profile Picture</label>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php
                                                $profilePicture = $user['profile_picture'] ?? null;
                                                $defaultAvatar = base_url('assets/defaultpic.jpg');

                                                if ($profilePicture && file_exists(FCPATH . $profilePicture)) {
                                                    $avatarSrc = base_url($profilePicture);
                                                } else {
                                                    $avatarSrc = $defaultAvatar;
                                                }
                                                ?>
                                                <img src="<?= esc($avatarSrc) ?>" alt="Profile Picture"
                                                    class="rounded-circle"
                                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #dee2e6;"
                                                    id="profilePicturePreview">

                                                <div class="flex-grow-1">
                                                    <p class="mb-1 text-muted">Upload a new profile picture</p>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" data-bs-target="#editProfilePictureModal">
                                                        <i class="bi bi-pencil"></i> Change Picture
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- RIGHT: First + Last Name (STACKED) -->
                                        <div class="col-md-6">
                                            <div class="row">

                                                <!-- First Name -->
                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-bold">First Name</label>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="mb-0"><?= esc($user['first_name'] ?? 'Not set') ?></p>
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal" data-bs-target="#editFirstNameModal">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Last Name -->
                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-bold">Last Name</label>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="mb-0"><?= esc($user['last_name'] ?? 'Not set') ?></p>
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal" data-bs-target="#editLastNameModal">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <!-- Position -->
                                        <div class="col-6 mb-3">
                                            <label class="form-label fw-bold">Position</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0"><?= esc($user['position'] ?? 'Not set') ?></p>
                                            </div>
                                        </div>
                                        <!-- Division/Unit -->
                                        <div class="col-6 mb-3">
                                            <label class="form-label fw-bold">Division/Unit</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0"><?= esc($user['division'] ?? 'Not set') ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Account Security Section -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="accountSecurityHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accountSecurityCollapse" aria-expanded="false" aria-controls="accountSecurityCollapse">
                                    <i class="bi bi-shield-lock me-2"></i> Account Security
                                </button>
                            </h2>
                            <div id="accountSecurityCollapse" class="accordion-collapse collapse" aria-labelledby="accountSecurityHeading" data-bs-parent="#accountSettingsAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Email Address</label>
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                <p class="mb-0"><?= esc($user['email'] ?? 'Not set') ?></p>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editEmailModal">
                                                    <i class="bi bi-pencil"></i> Update Email
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mt-1">You will be asked for your password to confirm email changes.</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Password</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0 text-muted">••••••••••••</p>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                    <i class="bi bi-pencil"></i> Change Password
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mt-1">Enter your current password to set a new one.</small>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Account Created</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0"><?= $user['created_at'] ? date('F d, Y', strtotime($user['created_at'])) : 'Not available' ?></p>
                                                <span class="text-muted"><small>Read-only</small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Deletion Section -->
                        <div class="accordion-item border-danger">
                            <h2 class="accordion-header" id="accountDeletionHeading">
                                <button class="accordion-button collapsed text-danger" type="button" data-bs-toggle="collapse" data-bs-target="#accountDeletionCollapse" aria-expanded="false" aria-controls="accountDeletionCollapse">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Account Deletion
                                </button>
                            </h2>
                            <div id="accountDeletionCollapse" class="accordion-collapse collapse" aria-labelledby="accountDeletionHeading" data-bs-parent="#accountSettingsAccordion">
                                <div class="accordion-body">
                                    <div class="alert alert-warning" role="alert">
                                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Warning!</h5>
                                        <p class="mb-0">Deleting your account is permanent and cannot be undone. All your data, including order history, will be permanently deleted.</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Delete Account</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="mb-0 text-muted">Permanently delete your account and all associated data</p>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                                    <i class="bi bi-trash"></i> Delete Account
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>






<!-- Edit Profile Picture Modal -->
<div class="modal fade" id="editProfilePictureModal" tabindex="-1" aria-labelledby="editProfilePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfilePictureModalLabel">Change Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfilePictureForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <img src="<?= esc($avatarSrc) ?>" alt="Profile Picture Preview" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #dee2e6;" id="profilePictureModalPreview">
                    </div>
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Select New Picture</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
                        <div class="form-text">Accepted formats: JPG, PNG, GIF. Max size: 5MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Picture</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit First Name Modal -->
<div class="modal fade" id="editFirstNameModal" tabindex="-1" aria-labelledby="editFirstNameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFirstNameModalLabel">Edit First Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFirstNameForm" method="post" action="<?= route_to('update.firstname') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Last Name Modal -->
<div class="modal fade" id="editLastNameModal" tabindex="-1" aria-labelledby="editLastNameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLastNameModalLabel">Edit Last Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLastNameForm" method="post" action="<?= route_to('update.lastname') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Email Modal -->
<div class="modal fade" id="editEmailModal" tabindex="-1" aria-labelledby="editEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmailModalLabel">Edit Email Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEmailForm" method="post" action="<?= route_to('update.email') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>" required>
                        <div class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="current_password_email" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password_email" name="current_password" placeholder="Enter your password to confirm" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                        <div class="form-text">Password must be at least 8 characters long.</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>Delete Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteAccountForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <strong>Warning!</strong> This action cannot be undone. All your data will be permanently deleted.
                    </div>
                    <p>To confirm account deletion, please enter your password:</p>
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="delete_password" name="delete_password" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            I understand that this action cannot be undone
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete My Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const csrfTokenName = '<?= csrf_token() ?>';
    let csrfTokenValue = '<?= csrf_hash() ?>';

    function setCsrfToken(newToken) {
        if (!newToken) {
            return;
        }
        csrfTokenValue = newToken;
        document.querySelectorAll(`input[name="${csrfTokenName}"]`).forEach((input) => {
            input.value = newToken;
        });
    }

    // Handle form submissions with AJAX
    document.addEventListener('DOMContentLoaded', function() {
        // Profile Picture Preview
        const profilePictureInput = document.getElementById('profile_picture');
        const profilePictureModalPreview = document.getElementById('profilePictureModalPreview');

        if (profilePictureInput && profilePictureModalPreview) {
            profilePictureInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePictureModalPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Edit Profile Picture
        document.getElementById('editProfilePictureForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            uploadProfilePicture();
        });

        // Change Password
        document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            changePassword();
        });

        // Delete Account
        document.getElementById('deleteAccountForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            deleteAccount();
        });
    });

    function uploadProfilePicture() {
        const form = document.getElementById('editProfilePictureForm');
        const formData = new FormData(form);

        // Validate file size (5MB max)
        const fileInput = document.getElementById('profile_picture');
        if (fileInput.files[0] && fileInput.files[0].size > 5 * 1024 * 1024) {
            showToast('error', 'File size must be less than 5MB');
            return;
        }
        formData.append(csrfTokenName, csrfTokenValue);

        fetch('<?= route_to('user.update-profile-picture') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfTokenValue
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                setCsrfToken(data.csrf_token ?? null);
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfilePictureModal'));
                    modal.hide();

                    // Update preview image
                    const preview = document.getElementById('profilePicturePreview');
                    if (preview && data.profile_picture_url) {
                        preview.src = data.profile_picture_url;
                    }

                    // Show success message
                    showToast('success', data.message || 'Profile picture updated successfully');

                    // Reload page after a short delay to update header image
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast('error', data.message || 'Failed to upload profile picture');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while uploading profile picture');
            });
    }

    function changePassword() {
        const formData = {
            current_password: document.getElementById('current_password').value,
            new_password: document.getElementById('new_password').value,
            confirm_password: document.getElementById('confirm_password').value
        };

        if (formData.new_password !== formData.confirm_password) {
            showToast('error', 'New passwords do not match');
            return;
        }

        fetch('<?= route_to('user.change-password') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfTokenValue
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                setCsrfToken(data.csrf_token ?? null);
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                    modal.hide();
                    document.getElementById('changePasswordForm').reset();
                    showToast('success', data.message || 'Password changed successfully');
                } else {
                    showToast('error', data.message || 'Failed to change password');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while changing password');
            });
    }

    function deleteAccount() {
        const formData = {
            password: document.getElementById('delete_password').value
        };

        if (!document.getElementById('confirmDelete').checked) {
            showToast('error', 'Please confirm that you understand this action cannot be undone');
            return;
        }

        fetch('<?= route_to('user.delete-account') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfTokenValue
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                setCsrfToken(data.csrf_token ?? null);
                if (data.success) {
                    showToast('success', data.message || 'Account deleted successfully');
                    setTimeout(() => {
                        window.location.href = '<?= route_to('home') ?>';
                    }, 2000);
                } else {
                    showToast('error', data.message || 'Failed to delete account');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while deleting account');
            });
    }

    function showToast(type, message) {
        // Simple toast notification - you can replace this with your preferred toast library
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
        return container;
    }
</script>
<?= $this->endSection() ?>