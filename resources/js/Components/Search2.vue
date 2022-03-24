<template>
    <div class="my-5 flex sm:flex-row flex-col">
        <div class="flex flex-row mb-1 sm:mb-0">
            <div class="relative">
                <select
                    class="rounded-l-md h-full block w-full border border-gray-500 text-gray-700 py-2 pl-2 pr-8 leading-tight focus:outline-none hover:outline-none active:outline-none focus:border-gray-600"
                >
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>
            </div>
        </div>
        <div class="flex flex-row mb-1 sm:mb-0">
            <div class="relative">
                <select
                    v-model="perPage"
                    class="h-full block w-full border border-gray-500 text-gray-700 py-2 pl-2 pr-8 leading-tight focus:outline-none hover:outline-none active:outline-none focus:border-gray-600"
                    @change="getData"
                >
                    <option value="5">05 Per Page</option>
                    <option value="10">10 Per Page</option>
                    <option value="15">15 Per Page</option>
                    <option value="30">30 Per Page</option>
                </select>
            </div>
        </div>
        <div class="block relative">
            <span
                class="h-full absolute inset-y-0 left-0 flex items-center pl-2"
            >
                <svg
                    class="h-4 w-4 fill-current text-gray-500"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z"
                    ></path>
                </svg>
            </span>
            <input
                v-model="search"
                class="block pl-8 pr-6 py-2 w-full bg-white text-gray-900 focus:bg-white focus:placeholder-gray-600 focus:text-gray-900 focus:outline-none focus:border-gray-600"
                placeholder="Search here ..."
                type="text"
            />
        </div>
        <button
            class="group px-3 py-2 bg-gray-800 hover:bg-gray-900 focus:text-gray-50 text-white text-sm font-medium rounded-r-md"
        >
            <span class="flex h-6 w-6 group-active:animate-around">
                <span class="rounded-full flex">
                    <svg
                        class="h-6 w-6 flex z-1"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </span>
            </span>
        </button>
    </div>
</template>
