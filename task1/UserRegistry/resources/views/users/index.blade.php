@extends('layouts.app')

@section('title', 'Users List')

@section('content')
<div class="min-h-screen max-w-screen flex justify-center">
    <div class="w-full">
        <div class="flex justify-center items-center relative w-full h-64 bg-cover" style="background-image: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80');">
            <div class="absolute top-0 left-0 w-full h-full bg-gray-900 opacity-50"></div>
            <div class="text-white text-4xl mb-8 relative z-10">
                User Listing
            </div>
        </div>
        <div id="toast" class="hidden fixed bottom-0 left-0 mb-4 ml-4 p-4 bg-gray-800 text-white rounded-md"></div>
        <div class="flex justify-center pt-16">
            <div class="w-1/2 flex justify-end">
                <button id="add-user" type="button" class="bg-gray-900 text-white px-4 py-2 ml-2">Add New User</button>
            </div>
        </div>

        <!-- User List Table -->
        <div class="flex justify-center pt-4">
            <table class="w-1/2">
                <tbody>
                    @foreach($users as $user)
                    <tr class="{{$loop->index%2==0?'bg-gray-200':'bg-white'}}">
                        <td class="px-4 py-2">{{$user->name}}</td>
                        <td class="px-4 py-2">{{$user->email}}</td>
                        <td class="px-4 py-2">
                            <button class="text-red-500 px-4 py-2 rounded delete-user" data-user-id="{{$user->id}}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="hidden fixed top-0 left-0 w-full h-full flex justify-center items-center bg-gray-900 bg-opacity-50">
    <div class="bg-white rounded p-8 w-2/5">
        <h2 class="text-2xl mb-4">Add new user</h2>
        <form id="addUserForm" action="{{ route('users.store') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" id="name" name="name" class="form-input w-full border border-gray-300 rounded py-2 px-4" placeholder="Enter your name">
            </div>
            <div class="mb-4">
                <label for="surname" class="block">Surname</label>
                <input type="text" id="surname" name="surname" class="w-full border border-gray-300 rounded py-2 px-4" placeholder="Enter your surname">
            </div>
            <div class="mb-4">
                <label for="email" class="block">Email</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded py-2 px-4" placeholder="Enter your email">
            </div>
            <div class="mb-4">
                <label for="position" class="block">Position</label>
                <input type="text" id="position" name="position" class="w-full border border-gray-300 rounded py-2 px-4" placeholder="Enter your position">
            </div>
            <div class="flex justify-end">
                <button type="reset" class="bg-gray-900 text-white px-8 py-2 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
                <button type="button" id="closeAddUserModal" class="ml-2 bg-gray-300 text-gray-700 px-8 py-2">Cancel</button>
                <button type="submit" class="bg-gray-900 text-white px-8 py-2 ml-2">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete User Confirmation Modal -->
<div id="deleteUserModal" class="hidden fixed top-0 left-0 w-full h-full flex justify-center items-center bg-gray-900 bg-opacity-50">
    <div class="bg-white rounded p-8 w-1/2">
        <h2 class="text-2xl mb-12">Confirm delete</h2>
        <p class="mb-12">Please confirm that you would like to delete this user.</p>
        <form id="deleteUserForm" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" id="deleteUserId" name="user_id">
            <div class="flex justify-end">
                <button type="button" id="closeDeleteUserModal" class="bg-gray-300 text-gray-700 px-8 py-2">Cancel</button>
                <button type="submit" class="bg-gray-900 text-white px-8 py-2 ml-2">Confirm</button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('script')
<script>
    // Add User Modal
    const addUserModal = document.getElementById('addUserModal');
    const addUserForm = document.getElementById('addUserForm');
    const closeAddUserModal = document.getElementById('closeAddUserModal');
    const addUserButton = document.getElementById('add-user');

    // Delete User Confirmation Modal
    const deleteUserModal = document.getElementById('deleteUserModal');
    const deleteUserForm = document.getElementById('deleteUserForm');
    const deleteUserIdInput = document.getElementById('deleteUserId');
    const closeDeleteUserModal = document.getElementById('closeDeleteUserModal');
    const deleteButtons = document.querySelectorAll('.delete-user');

    // Function to display a toast message
    function showToast(message) {
        const toastElement = document.getElementById('toast');
        toastElement.textContent = message;
        toastElement.classList.remove('hidden');

        // Hide the toast after 4 seconds
        setTimeout(function() {
            toastElement.classList.add('hidden');
        }, 4000);
    }

    // Show Add User Modal
    function showAddUserModal() {
        addUserModal.classList.remove('hidden');
    }

    // Hide Add User Modal
    function hideAddUserModal() {
        addUserModal.classList.add('hidden');
    }

    // Show Delete User Confirmation Modal
    function showDeleteUserModal(userId) {
        deleteUserIdInput.value = userId;
        deleteUserModal.classList.remove('hidden');
    }

    // Hide Delete User Confirmation Modal
    function hideDeleteUserModal() {
        deleteUserModal.classList.add('hidden');
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateForm() {
        // Get form field values
        const name = document.getElementById('name').value;
        const surname = document.getElementById('surname').value;
        const email = document.getElementById('email').value;
        const position = document.getElementById('position').value;
        // Clear existing error messages
        clearErrorMessages();

        // Track validation status
        let isValid = true;

        // Perform validation checks
        if (name.trim() === '') {
            isValid = false;
            displayErrorMessage('name', 'Please enter your name.');
        }

        if (surname.trim() === '') {
            isValid = false;
            displayErrorMessage('surname', 'Please enter your surname.');
        }

        if (email.trim() === '') {
            isValid = false;
            displayErrorMessage('email', 'Please enter your email address.');
        } else if (!isValidEmail(email)) {
            isValid = false;
            displayErrorMessage('email', 'Please enter a valid email address.');
        }

        if (position.trim() === '') {
            isValid = false;
            displayErrorMessage('position', 'Please enter your position.');
        }

        // Add more validation checks for other fields as needed

        // Return validation status
        return isValid;
    }

    function displayErrorMessage(fieldName, message) {
        const field = document.getElementById(fieldName);
        const errorMessage = document.createElement('p');
        errorMessage.className = 'text-red-500';
        errorMessage.textContent = message;
        field.parentNode.appendChild(errorMessage);
    }

    function clearErrorMessages() {
        const errorMessages = document.getElementsByClassName('text-red-500');
        while (errorMessages.length > 0) {
            errorMessages[0].parentNode.removeChild(errorMessages[0]);
        }
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Add User Modal
        closeAddUserModal.addEventListener('click', hideAddUserModal);

        addUserButton.addEventListener('click', function() {
            showAddUserModal();
        });

        // Delete User Confirmation Modal
        closeDeleteUserModal.addEventListener('click', hideDeleteUserModal);

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const userId = button.getAttribute('data-user-id');
                showDeleteUserModal(userId);
            });
        });

        // AJAX for Adding a User
        addUserForm.addEventListener('submit', function(event) {
            event.preventDefault();
            // Perform form validation
            if (validateForm()) {
                // If validation succeeds, submit the form
                const formData = new FormData(addUserForm);

                fetch(addUserForm.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Handle success or error response
                        console.log(data);
                        hideAddUserModal();
                        // You may refresh the user list or update it dynamically using JavaScript
                        location.reload();
                        // Display the toast message with the response message
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

        });

        // AJAX for Deleting a User
        // AJAX for Deleting a User
        deleteUserForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(deleteUserForm);
            const userId = deleteUserIdInput.value;
            const deleteUrl = `/users/${userId}`;

            fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-HTTP-Method-Override': 'DELETE',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Handle success or error response
                    console.log(data);
                    hideDeleteUserModal();
                    // You may refresh the user list or update it dynamically using JavaScript
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                });
        });

    });
</script>
@endsection