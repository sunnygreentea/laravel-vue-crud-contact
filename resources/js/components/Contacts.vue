<template>
    <div>
        <button class="btn btn-lg btn-success my-4 px-4" data-toggle="modal" data-target="#formModal" @click="clearForm()">Add New Contact</button>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchContacts(pagination.prev_page_url)">Previous</a></li>
                <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchContacts(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="contact in contacts" v-bind:key="contact.id">
                    <td>{{ contact.first_name }}</td>
                    <td>{{ contact.last_name }}</td>
                    <td>{{ contact.email }}</td>
                    <td>{{ contact.phone }}</td>
                    <td><button @click="showEditContact(contact)" class="btn btn-warning mb-2" data-toggle="modal" data-target="#formModal">Edit</button></td>
                    <td><button @click="deleteContact(contact.id)" class="btn btn-danger">Delete</button></td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">{{form_title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editContact(contact)" class="mb-3">
                    <div class="modal-body">
				            <div class="form-group">
				                <input type="text" class="form-control" placeholder="First name" v-model="contact.first_name">
				            </div>
				            <div class="form-group">
				            	<input type="text" class="form-control" placeholder="Last name" v-model="contact.last_name">
				            </div>
				            <div class="form-group">
				            	<input type="email" class="form-control" placeholder="Email" v-model="contact.email">
				            </div>
				            <div class="form-group">
				            	<input type="text" class="form-control" placeholder="Phone" v-model="contact.phone">
				            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            contacts: [],
            contact: {
                id: '',
                firstname: '',
                last_name: '',
                email: '',
                phone: '',
            },
            contact_id: '',
            pagination: {},
            edit: false,
            form_title: 'Add New Contact',
        }

    },

    created() {
        this.fetchContacts();
    },

    methods: {
    	
        fetchContacts(page_url) {
            let vm = this;
            page_url = page_url || 'api/contacts';
            axios.get(page_url)
                .then(res => {
                    console.log(res);
                    this.contacts = res.data.data;
                    vm.makePagination(res.data.meta, res.data.links);
                })
                .catch(err => console.log(err));
        },

        makePagination(meta, links) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            };
            this.pagination = pagination;
        },

        editContact() {
        	// Add
        	if(this.edit === false){
        		if(this.contact.first_name && this.contact.last_name && this.contact.email && this.contact.phone) {
        			axios.post('api/contact', this.contact)
                    .then((res) => {
                        this.clearForm();
                        this.fetchContacts();
                    }).catch((err) => {
                        console.log(err)
                    });
        		}
        		else {
        			confirm('Please enter the data!');
        		}
        		
        	}
        	// Update
        	else {
        		axios.put ('api/contact', this.contact)
        			.then( res =>{
        				this.clearForm();
                        this.fetchContacts();
        			})
        			.catch( err => {
        				console.log(err);
        			});

        	}
        },

        showEditContact (contact) {
        	this.edit = true;
        	this.form_title="Edit Contact";
        	this.contact.id = contact.id;
            this.contact.contact_id = contact.id;
            this.contact.first_name = contact.first_name;
            this.contact.last_name = contact.last_name;
            this.contact.email = contact.email;
            this.contact.phone = contact.phone;
            this.showModal = true;
        },

        deleteContact(id) {
            if (confirm('Are You Sure?' + id)) {
                let data = new FormData();
                data.append('_method', 'delete');
                console.log(data);
                axios.post('api/contact/' + id, data)
                    .then((res) => {
                        this.fetchContacts();
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            }
        },

        clearForm() {
        	this.edit = false;
        	this.form_title="Add New Contact";
        	this.contact.id = null;
            this.contact.contact_id = null;
            this.contact.first_name = '';
            this.contact.last_name = '';
            this.contact.email = '';
            this.contact.phone = '';
           
        }
    }
};

</script>
