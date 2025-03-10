<x-admin-layout>
    <h3 class="text-gray-700 text-3xl font-medium">Profile</h3>

    <div class="mt-8">
        <!-- Update Profile Information Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-700">Update Profile Information</h4>
            </div>
            <div class="p-6 text-gray-700">
                <div class="max-w-xl">
                    @include('admin.profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-700">Update Password</h4>
            </div>
            <div class="p-6 text-gray-700">
                <div class="max-w-xl">
                    @include('admin.profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account Section -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <h4 class="text-lg font-semibold text-red-700">Delete Account</h4>
            </div>
            <div class="p-6 text-gray-700">
                <div class="max-w-xl">
                    @include('admin.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message Alert (Similar to dashboard) -->
    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        @php
            $message =
                session('status') === 'profile-updated'
                    ? 'Your profile information has been updated successfully.'
                    : 'Your password has been updated successfully.';
            $colorClasses = 'bg-green-100 border-green-400 text-green-700';
        @endphp

        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show" x-transition
            class="fixed bottom-4 right-4 {{ $colorClasses }} px-4 py-3 rounded relative mb-4 border max-w-md shadow-lg"
            role="alert">
            {{ $message }}
        </div>
    @endif
</x-admin-layout>
