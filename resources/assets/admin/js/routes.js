//Profile
import HomeProfile from  './components/profile/homeComponent';
import PhotosProfile from  './components/profile/photosComponent'; 
import MessagesProfile from './components/profile/messagesComponent'; 
import TasksProfile from './components/profile/tasksComponent';
//Users
import IndexUsers from './components/users/indexComponent';
import TeamUsers from './components/users/teamComponent';
import ClientsUsers from './components/users/clientsComponent'; 
import StudentsUsers from './components/users/studentsComponent'; 

import AddUsers from './components/users/addComponent';
import EditUsers from './components/users/editComponent';

//Pages
import IndexPages from './components/pages/indexComponent';

import FacebookPages from './components/pages/facebook/indexComponent';
import AddFacebookPage from './components/pages/facebook/addComponent';

//Posts
import IndexPosts from './components/posts/indexComponent';
import AlbumPosts from './components/posts/albumComponent';
//Template
import ShowTemplate from './components/posts/templates/showComponent';
import IndexTemplate from './components/posts/templates/indexComponent';

//Clients
import IndexClients from './components/clients/indexComponent';
import AllClients from './components/clients/allComponent';
import AddClients from './components/clients/addComponent'; 
//Uploads
import IndexUploads from './components/uploads/indexComponent'; 
import AddUploads from './components/uploads/addComponent';
export const routes = [
    {
        path:'/admin/profile/:username',
        component: HomeProfile,
        name: 'admin.profile.home'
    },
    {
        path:'/admin/profile/:username/photos',
        component: PhotosProfile,
        name: 'admin.profile.photos'
    }, 
    {
        path:'/admin/profile/:username/tasks',
        component: TasksProfile,
        name: 'admin.profile.tasks'
    }, 
    {
        path:'/admin/messages/:username',
        component: MessagesProfile,
        name: 'admin.profile.messages'
    },
    //Users
    {
        path:'/admin/users',
        component: IndexUsers,
        name: 'admin.users.index'
    },
    {
        path:'/admin/users/team',
        component: TeamUsers,
        name: 'admin.users.team'
    },
    {
        path:'/admin/users/clients',
        component: ClientsUsers,
        name: 'admin.users.clients'
    },
    {
        path:'/admin/users/students',
        component: StudentsUsers,
        name: 'admin.users.students'
    },
    {
        path:'/admin/users/add',
        component: AddUsers,
        name: 'admin.users.add'
    },
    {
        path:'/admin/users/:id/edit',
        component: EditUsers,
        name: 'admin.users.edit'
    },

    {
        path:'/admin/pages',
        component: IndexPages,
        name: 'admin.pages.index'
    },

    {
        path:'/admin/pages/facebook',
        component: FacebookPages,
        name: 'admin.pages.facebook'
    },

    {
        path:'/admin/pages/facebook/add',
        component: AddFacebookPage,
        name: 'admin.pages.facebook.add'
    },

    {
        path:'/admin/posts',
        component: IndexPosts,
        name: 'admin.posts.index'
    }, 

    {
        path:'/admin/posts/album',
        component: AlbumPosts,
        name: 'admin.posts.album'
    },

    {
        path:'/admin/posts/template',
        component: IndexTemplate,
        name: 'admin.posts.template'
    },

    {
        path:'/admin/posts/template/:id',
        component: ShowTemplate,
        name: 'admin.posts.template.show'
    },

    {
        path:'/admin/clients',
        component: IndexClients,
        name: 'admin.clients.index'
    },

    {
        path:'/admin/clients/all',
        component: AllClients,
        name: 'admin.clients.all'
    },

    {
        path:'/admin/clients/add',
        component: AddClients,
        name: 'admin.clients.add'
    }, 

    {
        path:'/admin/uploads',
        component: IndexUploads,
        name: 'admin.uploads.index'
    },

    {
        path:'/admin/uploads/add',
        component: AddUploads,
        name: 'admin.uploads.add'
    },

];