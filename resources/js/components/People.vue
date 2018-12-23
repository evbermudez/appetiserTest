<template>
    <div>
        <h3 class="my-5">Appetiser Exam!</h3>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="errors.length">
            <h5 class="alert-heading">Please correct the following error(s):</h5>
            <hr>
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </div>

        <div class="d-flex">
            <div class="">
                <div class="card text-white bg-dark mb-3 mr-3">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fa fa-address-card"></i> User Form</h4>

                        <form @submit.prevent="addPerson" class="mb-2">
                            <div class="form-inline mb-2">
                                <input type="text" class="form-control mr-2" placeholder="First Name" v-model="person.first_name">
                                <input type="text" class="form-control mr-2" placeholder="Last Name" v-model="person.last_name">
                                <input type="date" class="form-control mr-2"  v-model="person.birthday">
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Save</button>
                        </form>

                        <button @click="clearForm()" class="btn btn-danger btn-block">Cancel</button>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="card text-white bg-primary mb-3 mr-2">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fa fa-user"></i> Search User</h4>
                        <div class="form-inline mb-2">

                            <select v-model="selectedFilter" class="form-control mr-2">
                                <option value="first_name">First Name</option>
                                <option value="last_name">Last Name</option>
                            </select>
                            <input type="text" class="form-control" v-model="search">
                        </div>
                    </div>
                </div>

                <div class="card text-white bg-primary mb-3 mr-2">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fa fa-clock-o"></i> Age Filter</h4>
                        <div class="form-inline mb-2">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">Enter Age:</label>
                                </div>
                                <input type="number" class="form-control " v-model="ageFilter">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchPeople(pagination.prev_page_url)">Previous</a></li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchPeople(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
        <div class="card-columns">
            <div class="card mb-2" v-for="person in filteredPeople" v-bind:key="person.id">
                <div class="card-body">
                    <h4 class="card-title">User #{{ person.id }}</h4>
                    <p class="card-text">
                        <p><strong>Name:</strong> {{ person.last_name }}, {{ person.first_name }}</p>
                        <p><strong>Birthday:</strong> {{ person.birthday}}</p>
                        <p><strong> Age:</strong> {{ person.age }}</p>
                    </p>
                </div>
                <div class="card-footer">
                    <button @click="editPerson(person)" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                    <button @click="deletePerson(person.id)" class="btn btn-danger"><i class="fa fa-remove"></i> Delete</button>
                </div>
            </div>
        </div>
        <FlashMessage></FlashMessage>
    </div>

    
</template>

<script>
export default {
    data() {
        return {
            people: [],
            person: {
                id: '',
                first_name: '',
                last_name: '',
                birthday: '',
                age: '',
            },
            person_id: '',
            pagination: {},
            edit: false,
            search: '',
            selectedFilter: 'first_name',
            errors: [],
            ageFilter: '',
        };
    },

    created() {
        this.fetchPeople();

        
        
    },

    methods: {
        fetchPeople(page_url) {
            let vm = this;
            page_url = page_url || '/api/people';
            fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.people = res.data;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
        },
        fetchPeopleByFname(first_name){
            let vm = this;
            console.log('----fname: ' + first_name);
            fetch(`/api/person/fname/${first_name}`, {
                method: 'get'
                })
                .then(res => res.json())
                .then(res => {
                    this.people = res.data;
                    console.log('this daw:' + this.people);
                    // vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
        },
        fetchPeopleByLname(last_name){
            let vm = this;
            console.log('----fname: ' + last_name);
            fetch(`/api/person/lname/${last_name}`, {
                method: 'get'
                })
                .then(res => res.json())
                .then(res => {
                    this.people = res.data;
                    
                    // vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
        },
/*         fetchPeopleByAge(age){
            let vm = this;
            // console.log('----fname: ' + last_name);
            fetch(`/api/person/age/${age}`, {
                method: 'get'
                })
                .then(res => res.json())
                .then(res => {
                    this.people = res.data;
                    
                    // vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
        }, */
        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        },
        deletePerson(id) {
            if (confirm('Are You Sure?')) {
                fetch(`api/person/${id}`, {
                method: 'delete'
                })
                .then(res => res.json())
                .then(data => {
                    // alert('Person Removed');
                    this.flashMessage.success({title: 'Record Removed', message: 'Transaction Successful!'})
                    this.fetchPeople();
                })
                .catch(err => console.log(err));
            }
        },
        addPerson() {
            this.errors = [];
            console.log(this.errors);
            if(!this.person.first_name || !this.person.last_name || !this.person.birthday){
            console.log('error');

                this.errors.push('All User Form Fields are Required.');
            } else {
            console.log('no errors');

                if (this.edit === false) {
                    // Add
                    fetch('api/person', {
                        method: 'post',
                        body: JSON.stringify(this.person),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.clearForm();
                        //alert('Person Added');
                        this.flashMessage.success({title: 'Record Added', message: 'Transaction successful!'})
                        this.fetchPeople();
                    })
                    .catch(err => console.log(err));
                } else {
                    // Update
                    fetch('api/person', {
                        method: 'put',
                        body: JSON.stringify(this.person),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.clearForm();
                        // alert('Person Updated');
                        this.flashMessage.show({status: 'success',title: 'Record Updated', message: 'Transaction successful!'})
                        // this.flashMessage.success({title: 'Record Updated', message: 'Transaction successful!'})

                        this.fetchPeople();
                    })
                    .catch(err => console.log(err));
                }
            }
        },
        editPerson(person) {
            this.edit = true;
            this.person.id = person.id;
            this.person.person_id = person.id;
            this.person.first_name = person.first_name;
            this.person.last_name = person.last_name;
            this.person.birthday = person.birthday;
            
        },
        clearForm() {
            this.edit = false;
            this.person.id = null;
            this.person.person_id = null;
            this.person.first_name = '';
            this.person.last_name = '';
            this.person.birthday = '';
            
        },
        showAddPersonForm() {
            this.showAddForm = !this.showAddForm;
        }
    },

    watch: {
            search: function (value) {
                let vm = this;
                let selectedFilter = vm.selectedFilter;

                if(value && selectedFilter == "first_name"){
                    this.fetchPeopleByFname(value);
                }
                if(value && selectedFilter == "last_name"){
                    this.fetchPeopleByLname(value);
                                
                } else {
                    this.fetchPeople();
                }
            }
            // ageFilter: function(value){
            //     let vm = this;
            //     if(value){
            //         this.fetchPeopleAge(value);

            //     } else {
            //         this.fetchPeople();
            //     }
            // }
    },

    computed: {
        filteredPeople() {
            let vm = this;
            let selectedFilter = vm.selectedFilter;
            let ageFilter = vm.ageFilter;
            let age = this.people.age;

            if(selectedFilter === "first_name" && ageFilter){
                return this.people.filter(person => {
                    
                        return person.first_name.toLowerCase().match(this.search.toLowerCase()) && String(person.age).match(ageFilter);
                    
                })
            }
            if(selectedFilter === "first_name" && !ageFilter){
                return this.people.filter(person => {
                    return person.first_name.toLowerCase().match(this.search.toLowerCase());
                })
            } 
            if(selectedFilter === "last_name" && ageFilter){
                
                    return this.people.filter(person => {
                        
                            return person.last_name.toLowerCase().match(this.search.toLowerCase()) && String(person.age).match(ageFilter);

                    })
                
                
            }
            if(selectedFilter === "last_name" && !ageFilter){
                return this.people.filter(person => {
                    return person.last_name.toLowerCase().match(this.search.toLowerCase());
                })
            }
            
        },

/*         filteredAge(){
            let vm =this;
            let ageFilter = vm.ageFilter;

            return this.people.filter(person => {

                    let age = this.people.age;
                    return String(person.age).match(this.ageFilter);
                    // return (age >= ageFrom && age <= ageTo);
                
            })

        }  */
    }
}
</script>