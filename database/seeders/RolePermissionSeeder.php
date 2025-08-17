<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\Permission;
use Modules\Role\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        // Create Permissions
        $permissions = [
            // E-commerce Permissions
            'ecommerce.*',
            'ecommerce.products.*',
            'ecommerce.products.create',
            'ecommerce.products.edit',
            'ecommerce.products.update',
            'ecommerce.products.delete',
            'ecommerce.orders.*',
            'ecommerce.orders.view',
            'ecommerce.orders.update',
            'ecommerce.inventory.*',
            'ecommerce.inventory.view',
            'ecommerce.inventory.update',
            'ecommerce.reports.view',

            // Career Permissions
            'career.*',
            'career.jobs.*',
            'career.jobs.create',
            'career.jobs.edit',
            'career.jobs.update',
            'career.jobs.delete',
            'career.applications.*',

            // HR Permissions
            'hr.*',
            'hr.employees.*',
            'hr.employees.create',
            'hr.employees.edit',
            'hr.employees.update',
            'hr.employees.delete',
            'hr.recruitment.*',

            // E-learning Permissions
            'elearning.*',
            'elearning.courses.*',
            'elearning.courses.create',
            'elearning.courses.edit',
            'elearning.courses.update',
            'elearning.courses.delete',
            'elearning.lessons.*',
            'elearning.lessons.create',
            'elearning.lessons.edit',
            'elearning.lessons.update',
            'elearning.lessons.delete',
            'elearning.students.*',
            'elearning.certificates.*',
            'elearning.certificates.create',
            'elearning.certificates.update',
            'elearning.certificates.view',
            'elearning.reports.view',

            // Marketing Permissions
            'marketing.*',
            'marketing.campaigns.*',
            'marketing.campaigns.create',
            'marketing.campaigns.edit',
            'marketing.campaigns.update',
            'marketing.campaigns.delete',
            'marketing.seo.*',
            'marketing.analytics.*',
            'marketing.emails.*',
            'marketing.emails.create',
            'marketing.emails.send',
            'marketing.emails.update',
            'marketing.emails.delete',

            // Blog Permissions
            'blog.*',
            'blog.posts.*',
            'blog.posts.create',
            'blog.posts.edit',
            'blog.posts.update',
            'blog.posts.delete',
            'blog.categories.*',
            'blog.categories.create',
            'blog.categories.edit',
            'blog.categories.update',
            'blog.categories.delete',
            'blog.tags.*',
            'blog.tags.create',
            'blog.tags.edit',
            'blog.tags.update',
            'blog.tags.delete',
            'blog.comments.*',
            'blog.comments.view',
            'blog.comments.approve',
            'blog.comments.delete',

            // Travel Permissions
            'travel.*',
            'travel.packages.*',
            'travel.packages.create',
            'travel.packages.edit',
            'travel.packages.update',
            'travel.packages.delete',
            'travel.bookings.*',
            'travel.bookings.view',
            'travel.bookings.update',
            'travel.itineraries.*',
            'travel.itineraries.create',
            'travel.itineraries.edit',
            'travel.itineraries.update',
            'travel.itineraries.delete',
            'travel.reports.view',

            // Support Permissions
            'support.*',
            'support.tickets.*',
            'support.tickets.view',
            'support.tickets.respond',
            'support.tickets.close',
            'support.faqs.*',
            'support.faqs.create',
            'support.faqs.edit',
            'support.faqs.update',
            'support.faqs.delete',

            'customer.*',
        ];

        // Create Permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Roles and Permissions Assignment
        $roles = [
            'Super Admin' => [
                'ecommerce.*',
                'career.*',
                'hr.*',
                'elearning.*',
                'marketing.*',
                'blog.*',
                'travel.*',
                'support.*',
            ],
            'Administrator' => [
                'ecommerce.*',
                'career.*',
                'hr.*',
                'elearning.*',
                'marketing.*',
                'blog.*',
                'travel.*',
                'support.*',
            ],
            'E-commerce Manager' => [
                'ecommerce.*',
                'ecommerce.products.*',
                'ecommerce.orders.*',
                'ecommerce.inventory.*',
                'ecommerce.reports.view',
            ],
            'Career Manager' => [
                'career.*',
                'career.jobs.*',
                'career.applications.*',
            ],
            'HR Manager' => [
                'hr.*',
                'hr.employees.*',
                'hr.recruitment.*',
            ],
            'E-learning Manager' => [
                'elearning.*',
                'elearning.courses.*',
                'elearning.lessons.*',
                'elearning.students.*',
                'elearning.certificates.*',
                'elearning.reports.view',
            ],
            'Marketing Manager' => [
                'marketing.*',
                'marketing.campaigns.*',
                'marketing.analytics.*',
                'marketing.emails.*',
                'marketing.seo.*',
            ],
            'Blog Manager' => [
                'blog.*',
                'blog.posts.*',
                'blog.categories.*',
                'blog.comments.*',
            ],
            'Travel Manager' => [
                'travel.*',
                'travel.packages.*',
                'travel.bookings.*',
                'travel.itineraries.*',
                'travel.reports.view',
            ],
            'Support Manager' => [
                'support.*',
                'support.tickets.*',
                'support.faqs.*',
            ],
            'Content Creator' => [
                'blog.posts.*', // Content Creator specific blog permissions
                'blog.posts.create',
                'blog.posts.edit',
                'blog.posts.update',
                'blog.posts.delete',
                'elearning.courses.*', // Content Creator specific e-learning permissions
                'elearning.courses.create',
                'elearning.courses.edit',
                'elearning.courses.update',
                'elearning.courses.delete',
                'marketing.campaigns.*', // Content Creator specific marketing permissions
                'marketing.campaigns.create',
                'marketing.campaigns.edit',
                'marketing.campaigns.update',
            ],
            'Editor' => [
                'blog.posts.*', // Explicit blog permissions for Editor
                'blog.posts.create',
                'blog.posts.edit',
                'blog.posts.update',
                'blog.posts.delete',
                'elearning.courses.*', // Explicit e-learning permissions for Editor
                'elearning.courses.create',
                'elearning.courses.edit',
                'elearning.courses.update',
                'elearning.courses.delete',
                'marketing.campaigns.*', // Explicit marketing permissions for Editor
                'marketing.campaigns.create',
                'marketing.campaigns.edit',
                'marketing.campaigns.update',
                'marketing.campaigns.delete',
            ],
            'Customer' => [
                'customer.*',
            ],
        ];

        // Create Roles and Assign Permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
