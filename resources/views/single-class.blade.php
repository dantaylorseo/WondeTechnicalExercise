<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Classes') }}
        </h2>
    </x-slot>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="overflow-hidden bg-white shadow sm:rounded-lg mb-4">
                <div class="px-4 py-5 sm:px-6">
                  <h3 class="text-lg font-medium leading-6 text-gray-900">Class Information</h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                  <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Class name</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $class->name }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Description</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $class->description }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                      <dt class="text-sm font-medium text-gray-500">Subject</dt>
                      <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $class->subject }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Students</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @foreach ($class->students->sortBy('full_name') as $student )
                                {{ $student->full_name }}<br>
                            @endforeach
                        </dd>
                      </div>
                  </dl>
                </div>
              </div>
        </div>
    </div>
</x-app-layout>
