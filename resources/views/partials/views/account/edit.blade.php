        <!-- VIEW 10: EDIT PROFILE / ALAMAT -->
        <section id="view-edit-profile" class="view-page w-full px-4 sm:px-6 md:px-10 lg:px-12 pt-20 md:pt-24 pb-24">
            <div class="mb-6">
                <button onclick="goBack()" class="text-xs font-semibold tracking-widest text-gray-500 hover:text-black uppercase transition-colors focus:outline-none cursor-pointer flex items-center space-x-2 group font-space-grotesk">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">&larr;</span>
                    <span>BACK</span>
                </button>
            </div>

            <div class="max-w-xl mx-auto space-y-6">
                <div class="text-center">
                    <h1 class="text-xl md:text-2xl font-bold font-space-grotesk uppercase text-black">Edit Profile & Address</h1>
                </div>

                <div id="edit-profile-error" class="hidden bg-red-100 text-red-700 p-3 text-sm border border-red-200"></div>
                <div id="edit-profile-success" class="hidden bg-green-100 text-green-700 p-3 text-sm border border-green-200"></div>

                <form onsubmit="handleEditProfileSubmit(event)" class="space-y-6 bg-white border border-gray-200 p-6 md:p-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Profile Picture</label>
                        <input type="file" id="edit-avatar" accept="image/*" class="w-full border border-gray-300 p-2 text-sm focus:outline-none focus:border-black font-inter bg-gray-50">
                        <p class="text-[10px] text-gray-500 mt-1 uppercase">Optional. Max 4MB.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Full Name *</label>
                        <input type="text" id="edit-name" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-inter">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Phone / WhatsApp *</label>
                        <input type="text" id="edit-phone" required class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-inter">
                    </div>
                    <div class="relative" id="notisse-datepicker-container">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Date of Birth</label>
                        
                        <!-- Hidden actual date value for form submission (YYYY-MM-DD) -->
                        <input type="hidden" id="edit-birthdate" name="birthdate">

                        <!-- Custom Luxury Trigger Field -->
                        <div id="datepicker-trigger" 
                             onclick="toggleNotisseDatePicker(event)"
                             class="w-full border border-gray-300 p-3 text-sm flex items-center justify-between bg-white cursor-pointer hover:border-black focus:border-black transition-colors select-none group">
                            <div class="flex items-center space-x-3 min-w-0">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="0" ry="0" stroke-width="1.8"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6" stroke-width="1.8"></line>
                                    <line x1="8" y1="2" x2="8" y2="6" stroke-width="1.8"></line>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke-width="1.8"></line>
                                </svg>
                                <span id="datepicker-display-text" class="text-gray-400 font-inter text-sm truncate">Select birth date (DD / MM / YYYY)</span>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <button type="button" id="datepicker-clear-btn" onclick="clearNotisseDatePicker(event)" class="hidden text-gray-400 hover:text-black text-sm font-bold px-1 leading-none cursor-pointer" title="Clear date">
                                    &times;
                                </button>
                                <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-black transition-transform duration-200" id="datepicker-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Bespoke Notisse Calendar Dropdown -->
                        <div id="notisse-calendar-dropdown" 
                             class="hidden absolute z-30 left-0 top-full mt-1.5 w-full sm:w-[340px] bg-white border border-black shadow-2xl p-4 font-space-grotesk select-none transition-all duration-200">
                            
                            <!-- Calendar Header: Month & Year Fast Jump -->
                            <div class="flex items-center justify-between mb-3 pb-3 border-b border-gray-100">
                                <button type="button" onclick="changeNotisseMonth(-1, event)" class="p-1.5 text-black hover:bg-neutral-100 transition-colors cursor-pointer" title="Previous Month">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>

                                <!-- Month & Year Dropdowns -->
                                <div class="flex items-center space-x-2">
                                    <select id="cal-month-select" onchange="onMonthSelectChange(this.value)" class="bg-neutral-100 hover:bg-neutral-200 text-black font-space-grotesk font-bold text-xs uppercase px-2 py-1.5 border-none focus:outline-none cursor-pointer transition-colors">
                                        <option value="0">JANUARY</option>
                                        <option value="1">FEBRUARY</option>
                                        <option value="2">MARCH</option>
                                        <option value="3">APRIL</option>
                                        <option value="4">MAY</option>
                                        <option value="5">JUNE</option>
                                        <option value="6">JULY</option>
                                        <option value="7">AUGUST</option>
                                        <option value="8">SEPTEMBER</option>
                                        <option value="9">OCTOBER</option>
                                        <option value="10">NOVEMBER</option>
                                        <option value="11">DECEMBER</option>
                                    </select>

                                    <select id="cal-year-select" onchange="onYearSelectChange(this.value)" class="bg-neutral-100 hover:bg-neutral-200 text-black font-space-grotesk font-bold text-xs uppercase px-2 py-1.5 border-none focus:outline-none cursor-pointer transition-colors">
                                        <!-- Populated dynamically via JS -->
                                    </select>
                                </div>

                                <button type="button" onclick="changeNotisseMonth(1, event)" class="p-1.5 text-black hover:bg-neutral-100 transition-colors cursor-pointer" title="Next Month">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>

                            <!-- Day Names -->
                            <div class="grid grid-cols-7 text-center text-[10px] font-space-grotesk font-bold uppercase text-gray-400 mb-2 tracking-wider">
                                <span>SU</span>
                                <span>MO</span>
                                <span>TU</span>
                                <span>WE</span>
                                <span>TH</span>
                                <span>FR</span>
                                <span>SA</span>
                            </div>

                            <!-- Days Grid -->
                            <div id="cal-days-grid" class="grid grid-cols-7 gap-1 text-center font-space-grotesk text-xs">
                                <!-- Populated dynamically -->
                            </div>

                            <!-- Calendar Footer -->
                            <div class="flex items-center justify-between pt-3 mt-3 border-t border-gray-100">
                                <button type="button" onclick="clearNotisseDatePicker(event)" class="text-gray-400 hover:text-black font-space-grotesk font-semibold uppercase tracking-wider text-[10px] cursor-pointer">
                                    Clear
                                </button>
                                <button type="button" onclick="closeNotisseDatePicker()" class="bg-black text-white px-3.5 py-1.5 text-[10px] font-space-grotesk font-bold uppercase tracking-widest hover:bg-neutral-800 transition-colors cursor-pointer">
                                    Done
                                </button>
                            </div>

                        </div>
                    </div>
                    
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Search District / City *</label>
                        <input type="text" id="edit-area-search" class="w-full border border-gray-300 p-3 text-sm focus:outline-none focus:border-black font-inter" placeholder="Type district or city name..." autocomplete="off">
                        <ul id="edit-area-results" class="absolute z-20 w-full bg-white border border-gray-300 shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></ul>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1 font-space-grotesk">Full Address *</label>
                        <textarea id="edit-address" required class="w-full border border-gray-300 p-3 text-sm h-24 focus:outline-none focus:border-black font-inter resize-none" placeholder="Street name, unit, house number, landmarks..."></textarea>
                    </div>

                    <input type="hidden" id="edit-area-id" required>

                    <div class="pt-4 border-t border-gray-100 flex gap-3">
                        <button type="button" onclick="goBack()" class="w-1/3 border border-gray-300 py-3.5 text-xs font-bold font-space-grotesk uppercase hover:bg-gray-50 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" id="edit-profile-btn" class="w-2/3 bg-black text-white font-bold font-space-grotesk uppercase py-3.5 text-xs tracking-wider hover:bg-neutral-800 transition-colors cursor-pointer">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </section>

