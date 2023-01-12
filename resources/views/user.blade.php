<!-- We put our HTML code inside template tag -->
<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <!-- Button "add new user". When clicked, it will call /showModal function (function to display modal pop up containing "add new user" form). -->
                            <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" @click.prevent="showModal"><i class="fas fa-user-plus"></i>Add new user</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <!-- Data-table with pagination for user list. -->
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Loop through each user record and display user details -->
                        <tr v-for="user in users.data" :key="user.id">
                            <td class="align-middle">{{ user.name }}</td>
                            <td class="align-middle">{{ user.email }}</td>
                            <td class="align-middle">
                                <a href="" @click.prevent="editUser(user)">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="" @click.prevent="deleteUser(user.id)">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example" class="pagination-container">
                        <pagination :data="users" @pagination-change-page="getUsers"></pagination>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Modal containing dynamic form for adding/updating user details. -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- Show/hide headings dynamically based on /isFormCreateUserMode value (true/false) -->
                        <h5 v-show="isFormCreateUserMode" class="modal-title" id="exampleModalLabel">Add new user</h5>
                        <h5 v-show="!isFormCreateUserMode" class="modal-title" id="exampleModalLabel">Update user</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <!-- Form for adding/updating user details. When submitted call /createUser() function if /isFormCreateUserMode value is true. Otherwise call /updateUser() function. -->
                    <form @submit.prevent="isFormCreateUserMode ? createUser() : updateUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="name" placeholder="Email"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" placeholder="Password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" v-show="isFormCreateUserMode">Save changes</button>
                            <button type="submit" class="btn btn-primary" v-show="!isFormCreateUserMode">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- We put our scripts inside script tag -->
<script>
    // Declare /user-management component
    export default {
        name: 'user-management',
        // Declare users (as object), form (as /vform instance) and /isFormCreateUserMode (as boolean defaulted to 'true') inside /data() { return {} }.
        data() {
            return {
                users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: ''
                }),
                isFormCreateUserMode: true
            }
        },

        methods: {
            // /getUsers() function. Function we use to get user list by calling api/users method GET.
            getUsers(page) {
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get('api/users', {
                    params: {
                        page: page
                    }
                }).then(data => {
                    this.users = data.data;
                });
            },
            // /showModal() function. Function we use to 1. Set /isFormCreateUserMode to 'true', 2. Reset form data, 3. Show modal containing dynamic form for adding/updating user details.
            showModal() {
                this.isFormCreateUserMode = true;
                this.form.reset(); // v form reset
                $('#exampleModal').modal('show'); // show modal
            },
            // /createUser() function. Function we use to store user details by calling api/users method POST (carrying form input data).
            createUser() {
                // request post
                this.form.post('api/users', {
                }).then(() => {
                    $('#exampleModal').modal('hide'); // hide modal

                    // sweet alert 2
                    swal.fire({
                        icon: 'success',
                        title: 'User created successfully'
                    })

                    this.getUsers();

                }).catch(() => {
                    console.log('transaction fail');
                });
            },
            // /editUser() function. Function we use to 1. Set /isFormCreateUserMode to 'false', 2. Reset and clear form data, 3. Show modal containing dynamic form for adding/updating user details, 4. Fill form with user details.
            editUser(user){
                this.isFormCreateUserMode = false;
                this.form.reset(); // v form reset inputs
                this.form.clear(); // v form clear errors
                $('#exampleModal').modal('show'); // show modal
                this.form.fill(user);
            },
            // /updateUser() function. Function we use to update user details by calling api/users/{id} method PUT (carrying form input data).
            updateUser(){
                // request put
                this.form.put('api/users/' + this.form.id, {
                }).then(() => {
                    $('#exampleModal').modal('hide'); // hide modal

                    // sweet alert 2
                    swal.fire({
                        icon: 'success',
                        title: 'User updated successfully'
                    })

                    this.getUsers();
                }).catch(() => {
                    console.log('transaction fail');
                });
            },
            // /deleteUser() function. Function we use to delete user record by calling api/users/{id} method DELETE.
            deleteUser(id) {
                // sweet alert confirmation
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    // confirm delete?
                    if (result.value) {
                        // request delete
                        this.form.delete('api/users/' + id, {
                        }).then(() => {
                            // sweet alert success
                            swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )

                            this.getUsers(); // reload table users
                        }).catch(() => {
                            // sweet alert fail
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: '<a href>Why do I have this issue?</a>'
                            })
                        });
                    }
                })
            }
        },
        created() {
            // Call /getUsers() function initially.
            this.getUsers();
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<!-- We put our custom css/scss inside style (type can be css or scss) -->
<style type="scss">
    .pagination-container {
        height: 75px;
        display: grid;
    }
    .pagination {
        margin: auto !important;
    }
</style>