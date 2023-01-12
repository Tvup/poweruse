<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Metering point</h3>
          <div class="card-tools">
            <div class="input-group input-group-sm">
              <!-- Button "add new user". When clicked, it will call /showModal function (function to display modal pop up containing "add new user" form). -->
              <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                      @click.prevent="showModal"><i class="fas fa-user-plus"></i>Add new metering point
              </button>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <!-- Data-table with pagination for user list. -->
          <table class="table table-hover">
            <thead>
            <tr>
              <th>metering_point_id</th>
              <th>type_of_mp</th>
              <th>settlement_method</th>
              <th>meter_number</th>
              <th>consumer_c_v_r</th>
              <th>data_access_c_v_r</th>
              <th>consumer_start_date</th>
              <th>meter_reading_occurrence</th>
              <th>balance_supplier_name</th>
              <th>street_code</th>
              <th>street_name</th>
              <th>building_number</th>
              <th>floor_id</th>
              <th>room_id</th>
              <th>city_name</th>
              <th>city_sub_division_name</th>
              <th>municipality_code</th>
              <th>location_description</th>
              <th>first_consumer_party_name</th>
              <th>second_consumer_party_name</th>
              <th>hasRelation</th>
              <th></th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            <!-- Loop through each user record and display user details -->
            <tr v-for="metering_point in metering_points" :key="metering_point.id">
              <td class="align-middle">{{ metering_point.metering_point_id }}</td>
              <td class="align-middle">{{ metering_point.type_of_mp }}</td>
              <td class="align-middle">{{ metering_point.settlement_method }}</td>
              <td class="align-middle">{{ metering_point.meter_number }}</td>
              <td class="align-middle">{{ metering_point.consumer_c_v_r }}</td>
              <td class="align-middle">{{ metering_point.data_access_c_v_r }}</td>
              <td class="align-middle">{{ metering_point.consumer_start_date }}</td>
              <td class="align-middle">{{ metering_point.meter_reading_occurrence }}</td>
              <td class="align-middle">{{ metering_point.balance_supplier_name }}</td>
              <td class="align-middle">{{ metering_point.street_code }}</td>
              <td class="align-middle">{{ metering_point.street_name }}</td>
              <td class="align-middle">{{ metering_point.building_number }}</td>
              <td class="align-middle">{{ metering_point.floor_id }}</td>
              <td class="align-middle">{{ metering_point.room_id }}</td>
              <td class="align-middle">{{ metering_point.city_name }}</td>
              <td class="align-middle">{{ metering_point.city_sub_division_name }}</td>
              <td class="align-middle">{{ metering_point.municipality_code }}</td>
              <td class="align-middle">{{ metering_point.location_description }}</td>
              <td class="align-middle">{{ metering_point.first_consumer_party_name }}</td>
              <td class="align-middle">{{ metering_point.second_consumer_party_name }}</td>
              <td class="align-middle">{{ metering_point.hasRelation }}</td>
              <td class="align-middle">
                <a href="" @click.prevent="editMeteringPoint(metering_point)">
                  <i class="fa fa-edit"></i>
                </a>
                <a href="" @click.prevent="deleteMeteringPoint(metering_point.id)">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
            </tbody>
          </table>
          <nav aria-label="Page navigation example" class="pagination-container">
            <pagination :data="metering_points" @pagination-change-page="getMeteringPoints" :pageCount="last_page"></pagination>
          </nav>
        </div>
      </div>
    </div>

    <!-- Modal containing dynamic form for adding/updating user details. -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <!-- Show/hide headings dynamically based on /isFormCreateMeteringPointMode value (true/false) -->
            <h5 v-show="isFormCreateMeteringPointMode" class="modal-title" id="exampleModalLabel">Add new user</h5>
            <h5 v-show="!isFormCreateMeteringPointMode" class="modal-title" id="exampleModalLabel">Update user</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <!-- Form for adding/updating user details. When submitted call /createUser() function if /isFormCreateMeteringPointMode value is true. Otherwise call /updateUser() function. -->
          <form @submit.prevent="isFormCreateMeteringPointMode ? createMeteringPoint() : updateMeteringPoint()">
            <div class="modal-body">
              <div class="form-group">
                <input v-model="form.metering_point_id" type="metering_point_id" name="metering_point_id"
                       placeholder="Metering point id"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('metering_point_id') }">
                <has-error :form="form" field="metering_point_id"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.type_of_mp" type="type_of_mp" name="type_of_mp"
                       placeholder="Type of metering point"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('type_of_mp') }">
                <has-error :form="form" field="type_of_mp"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.settlement_method" type="settlement_method" name="settlement_method"
                       placeholder="Settlement method"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('settlement_method') }">
                <has-error :form="form" field="settlement_method"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.meter_number" type="meter_number" name="meter_number" placeholder="Meter number"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('meter_number') }">
                <has-error :form="form" field="meter_number"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.consumer_c_v_r" type="consumer_c_v_r" name="consumer_c_v_r"
                       placeholder="Consumer CVR"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('consumer_c_v_r') }">
                <has-error :form="form" field="consumer_c_v_r"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.data_access_c_v_r" type="data_access_c_v_r" name="consumer_c_v_r"
                       placeholder="Data access CVR"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('data_access_c_v_r') }">
                <has-error :form="form" field="data_access_c_v_r"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.consumer_start_date" type="consumer_start_date" name="consumer_start_date"
                       placeholder="Consumer start date"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('consumer_start_date') }">
                <has-error :form="form" field="consumer_start_date"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.meter_reading_occurrence" type="meter_reading_occurrence"
                       name="meter_reading_occurrence" placeholder="Meter reading occurrence"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('meter_reading_occurrence') }">
                <has-error :form="form" field="meter_reading_occurrence"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.balance_supplier_name" type="meter_reading_occurrence" name="balance_supplier_name"
                       placeholder="Balance supplier name"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('balance_supplier_name') }">
                <has-error :form="form" field="balance_supplier_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.street_code" type="text" name="street_code" placeholder="Street code"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('street_code') }">
                <has-error :form="form" field="street_code"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.street_name" type="text" name="street_name" placeholder="Street name"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('street_name') }">
                <has-error :form="form" field="street_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.building_number" type="text" name="building_number" placeholder="Building number"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('building_number') }">
                <has-error :form="form" field="building_number"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.floor_id" type="text" name="floor_id" placeholder="Floor id"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('floor_id') }">
                <has-error :form="form" field="floor_id"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.room_id" type="text" name="room_id" placeholder="Room id"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('room_id') }">
                <has-error :form="form" field="room_id"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.city_name" type="text" name="city_name" placeholder="City name"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('city_name') }">
                <has-error :form="form" field="city_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.city_sub_division_name" type="text" name="city_sub_division_name"
                       placeholder="City sub division name"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('city_sub_division_name') }">
                <has-error :form="form" field="city_sub_division_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.municipality_code" type="text" name="municipality_code"
                       placeholder="Municipality code"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('municipality_code') }">
                <has-error :form="form" field="municipality_code"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.location_description" type="text" name="location_description"
                       placeholder="Location description"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('location_description') }">
                <has-error :form="form" field="location_description"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.first_consumer_party_name" type="text" name="first_consumer_party_name"
                       placeholder="First consumer party name"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('first_consumer_party_name') }">
                <has-error :form="form" field="first_consumer_party_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.second_consumer_party_name" type="text" name="second_consumer_party_name"
                       placeholder="Second consumer party name"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('second_consumer_party_name') }">
                <has-error :form="form" field="second_consumer_party_name"></has-error>
              </div>
              <div class="form-group">
                <input v-model="form.hasRelation" type="text" name="hasRelation" placeholder="Has Relation"
                       class="form-control" :class="{ 'is-invalid': form.errors.has('hasRelation') }">
                <has-error :form="form" field="hasRelation"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" v-show="isFormCreateMeteringPointMode">Save changes</button>
              <button type="submit" class="btn btn-primary" v-show="!isFormCreateMeteringPointMode">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- We put our scripts inside script tag -->
<script>
import Form from "vform";
// Declare /user-management component
export default {
  props: {
    list: ''
  },
  // Declare users (as object), form (as /vform instance) and /isFormCreateMeteringPointMode (as boolean defaulted to 'true') inside /data() { return {} }.
  data() {
    return {
      metering_points: [],
      form: new Form({
        id: '',
        metering_point_id: '',
        type_of_mp: '',
        settlement_method: '',
        meter_number: '',
        consumer_c_v_r: '',
        data_access_c_v_r: '',
        consumer_start_date: '',
        meter_reading_occurrence: '',
        balance_supplier_name: '',
        street_code: '',
        street_name: '',
        building_number: '',
        floor_id: '',
        room_id: '',
        city_name: '',
        city_sub_division_name: '',
        municipality_code: '',
        location_description: '',
        first_consumer_party_name: '',
        second_consumer_party_name: '',
        hasRelation: ''
      }),
      isFormCreateMeteringPointMode: true,
      last_page: 0
    }
  },

  methods: {
    // /getUsers() function. Function we use to get user list by calling api/users method GET.
    getMeteringPoints(page) {
      if (typeof page === 'undefined') {
        page = 1;
      }

      axios.get('api/meteringPoint', {
        params: {
          page: page
        }
      }).then(data => {
        this.metering_points = data.data.data;
        this.last_page = data.data.last_page;
      });
    },
    // /showModal() function. Function we use to 1. Set /isFormCreateMeteringPointMode to 'true', 2. Reset form data, 3. Show modal containing dynamic form for adding/updating user details.
    showModal() {
      this.isFormCreateMeteringPointMode = true;
      this.form.reset(); // v form reset
      $('#exampleModal').modal('show'); // show modal
    },
    // /createUser() function. Function we use to store user details by calling api/users method POST (carrying form input data).
    createMeteringPoint() {
      // request post
      this.form.post('api/meteringPoint', {}).then(() => {
        $('#exampleModal').modal('hide'); // hide modal

        // sweet alert 2
        // swal.fire({
        //     icon: 'success',
        //     title: 'Metering Point created successfully'
        // })

        this.getMeteringPoints();

      }).catch(() => {
        console.log('transaction fail');
      });
    },
    // /editUser() function. Function we use to 1. Set /isFormCreateMeteringPointMode to 'false', 2. Reset and clear form data, 3. Show modal containing dynamic form for adding/updating user details, 4. Fill form with user details.
    editMeteringPoint(metering_point) {
      this.isFormCreateMeteringPointMode = false;
      this.form.reset(); // v form reset inputs
      this.form.clear(); // v form clear errors
      $('#exampleModal').modal('show'); // show modal
      this.form.fill(metering_point);
    },
    // /updateUser() function. Function we use to update user details by calling api/users/{id} method PUT (carrying form input data).
    updateMeteringPoint() {
      // request put
      this.form.put('api/meteringPoint/' + this.form.id, {}).then(() => {
        $('#exampleModal').modal('hide'); // hide modal

        // sweet alert 2
        // swal.fire({
        //     icon: 'success',
        //     title: 'User updated successfully'
        // })

        this.getMeteringPoints();
      }).catch(() => {
        console.log('transaction fail');
      });
    },
    // /deleteUser() function. Function we use to delete user record by calling api/users/{id} method DELETE.
    deleteMeteringPoint(id) {
      // sweet alert confirmation
      // swal.fire({
      //     title: 'Are you sure?',
      //     text: "You won't be able to revert this!",
      //     icon: 'warning',
      //     showCancelButton: true,
      //     confirmButtonColor: '#3085d6',
      //     cancelButtonColor: '#d33',
      //     confirmButtonText: 'Yes, delete it!'
      // }).then((result) => {
      //     // confirm delete?
      //     if (result.value) {
      //         // request delete
      //         this.form.delete('api/meteringPoint/' + id, {
      //         }).then(() => {
      //             // sweet alert success
      //             swal.fire(
      //                 'Deleted!',
      //                 'Your file has been deleted.',
      //                 'success'
      //             )
      //
      //             this.getMeteringPoints(); // reload table users
      //         }).catch(() => {
      //             // sweet alert fail
      //             swal.fire({
      //                 icon: 'error',
      //                 title: 'Oops...',
      //                 text: 'Something went wrong!',
      //                 footer: '<a href>Why do I have this issue?</a>'
      //             })
      //         });
      //     }
      // })
    }
  },
  created() {
    this.getMeteringPoints();
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