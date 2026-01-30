<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Carolina East Africa Foundation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans">
<div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">

    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">


             <!-- ================= FULL WIDTH SECTION 1 ================= -->
<header class="sticky top-0 z-50 w-full">

<!-- Contact bar -->
<div class="w-full bg-purple-600 border-b relative">
  <div class="max-w-7xl mx-auto px-6 flex justify-between py-3 text-sm items-center">

    <!-- Visible part -->
    <span class="text-white text-lg font-medium">Questions? Get in touch today</span>

    <!-- Mobile toggle -->
    <div class="lg:hidden">
      <input type="checkbox" id="contact-toggle" class="hidden peer" />
      <label for="contact-toggle" class="text-white font-medium cursor-pointer">Show contact info</label>

      <!-- Hidden contact info -->
      <div class="hidden peer-checked:flex flex-col mt-2 text-white font-medium space-y-1">
      <div class="flex flex-col lg:flex-row items-center lg:justify-between gap-4 text-gray-700">

        <!-- Phone -->
        <a href="tel:+448000246121" class="flex items-center gap-2 hover:text-purple-600 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l.4 2M7 8h2l.4 2M11 11h2l.4 2M15 14h2l.4 2M19 17h2l.4 2"/>
            </svg>
            0800 0246 121
        </a>

        <!-- Email -->
        <a href="mailto:info@ceaf.org" class="flex items-center gap-2 hover:text-purple-600 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0l4-4m-4 4l4 4"/>
            </svg>
            info@ceaf.org
        </a>

        <!-- Hours -->
        <span class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Mon–Fri: 9:00 AM – 5:00 PM
        </span>

    </div>


        <div class="flex gap-3">
          <a href="#" class="hover:text-gray-300" aria-label="Facebook">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M22,12a10,10,0,1,0-11.5,9.9v-7h-3v-3h3v-2.3c0-3,1.8-4.7,4.5-4.7a18.3,18.3,0,0,1,2.7.2v3h-1.5c-1.5,0-2,1-2,2v1.8h3l-.5,3h-2.5v7A10,10,0,0,0,22,12Z"/>
            </svg>
          </a>
          <a href="#" class="hover:text-gray-300" aria-label="Twitter">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M22.46,6c-.77.35-1.6.59-2.46.69a4.3,4.3,0,0,0,1.88-2.38,8.59,8.59,0,0,1-2.72,1.04,4.28,4.28,0,0,0-7.3,3.9A12.14,12.14,0,0,1,3.1,4.8a4.28,4.28,0,0,0,1.33,5.7,4.25,4.25,0,0,1-1.94-.54v.05a4.28,4.28,0,0,0,3.44,4.19,4.29,4.29,0,0,1-1.93.07,4.28,4.28,0,0,0,4,2.98A8.58,8.58,0,0,1,2,19.54a12.1,12.1,0,0,0,6.56,1.92c7.88,0,12.2-6.53,12.2-12.2,0-.19,0-.39-.01-.58A8.72,8.72,0,0,0,24,5.13a8.53,8.53,0,0,1-2.54.7Z"/>
            </svg>
          </a>
          <a href="#" class="hover:text-gray-300" aria-label="Instagram">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
              <path d="M7,2H17a5,5,0,0,1,5,5V17a5,5,0,0,1-5,5H7a5,5,0,0,1-5-5V7A5,5,0,0,1,7,2ZM12,7.5A4.5,4.5,0,1,0,16.5,12,4.5,4.5,0,0,0,12,7.5Zm5.5-.75a1.25,1.25,0,1,0,1.25,1.25A1.25,1.25,0,0,0,17.5,6.75Z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>

    <!-- Desktop full info -->
    <div class="hidden lg:flex items-center gap-6 text-white">
      <a href="tel:+448000246121" class="hover:text-gray-300 font-semibold flex items-center gap-1">📞 0800 0246 121</a>
      <a href="mailto:info@ceaf.org" class="hover:text-gray-300 font-semibold flex items-center gap-1">✉️ info@ceaf.org</a>
      <span class="flex items-center gap-1">⏰ Mon–Fri: 9:00 AM – 5:00 PM</span>
      <div class="flex items-center gap-3">
        <a href="#" class="hover:text-gray-300" aria-label="Facebook">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 5 3.66 9.13 8.44 9.88v-6.99H7.9v-2.89h2.54V9.77c0-2.5 1.49-3.88 3.77-3.88 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.89h-2.34v6.99C18.34 21.13 22 17 22 12z"/>
        </svg>
        </a>

        <!-- Twitter -->
        <a href="#" class="hover:text-gray-300" aria-label="Twitter">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0022.4.36a9.08 9.08 0 01-2.88 1.1A4.52 4.52 0 0016.45 0c-2.5 0-4.5 2-4.5 4.5 0 .35.04.7.11 1.03C7.69 5.42 4.07 3.33 1.64.29a4.5 4.5 0 00-.61 2.27c0 1.56.8 2.93 2.02 3.73A4.52 4.52 0 012 5.2v.05c0 2.18 1.55 4 3.6 4.42a4.52 4.52 0 01-2.03.08 4.51 4.51 0 004.21 3.14A9.05 9.05 0 010 19.54 12.8 12.8 0 006.92 21c8.3 0 12.85-6.88 12.85-12.85 0-.2 0-.42-.01-.63A9.2 9.2 0 0023 3z"/>
        </svg>
        </a>

        <!-- Instagram -->
        <a href="#" class="hover:text-gray-300" aria-label="Instagram">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm0 2h10c1.65 0 3 1.35 3 3v10c0 1.65-1.35 3-3 3H7c-1.65 0-3-1.35-3-3V7c0-1.65 1.35-3 3-3zm5 2a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-.75a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z"/>
        </svg>
        </a>
      </div>
    </div>

  </div>
</div>



  <!-- Main header -->
  <div class="w-full bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-5">

      <!-- Logo -->
      <a href="/" class="flex-shrink-0">
        <img src="{{ asset('images/carolina-logo.png') }}" alt="Carolina East Africa Foundation" class="h-20 w-auto">
      </a>

      <!-- Desktop nav -->
   <nav class="hidden lg:flex items-center gap-8 font-medium relative">

  <a href="/" class="hover:text-purple-600">Home</a>
  <a href="#about-ceaf" class="hover:text-purple-600">Our Work</a>

  <!-- Your Loss dropdown -->
  <div class="relative group">
    <button class="hover:text-purple-600 flex items-center gap-1 py-2 px-3">
      Your Loss
      <svg class="w-4 h-4 mt-0.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Dropdown -->
  <div class="absolute left-0 mt-1 w-60 bg-white shadow-lg rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">
      <a href="/bereaved-children" class="block px-4 py-3 hover:bg-purple-200">Bereaved Children</a>
      <a href="/bereaved-parents" class="block px-4 py-3 hover:bg-purple-200">Bereaved Parents</a>
      <a href="/bereaved-siblings" class="block px-4 py-3 hover:bg-purple-200">Bereaved Siblings</a>
      <a href="/bereaved-spouses" class="block px-4 py-3 hover:bg-purple-200">Bereaved Spouses</a>
      <a href="/what-to-do" class="block px-4 py-3 hover:bg-purple-200">What to Do & Expect</a>
    </div>
  </div>

  <a href="/register" class="hover:text-purple-600">Get Involved</a>
  <a href="/contact" class="hover:text-purple-600">Contact Us</a>
  <a href="/donate" class="bg-purple-600 text-white px-5 py-2 rounded-md font-semibold hover:bg-purple-700 transition">Donate</a>

</nav>


      <!-- Mobile menu toggle using checkbox -->
        <div class="lg:hidden relative">
        <!-- Hamburger wrapper -->
        <input type="checkbox" id="menu-toggle" class="hidden peer" />
        <label for="menu-toggle" class="flex justify-start px-6 py-5 items-center cursor-pointer z-50">
            <span class="block w-6 h-0.5 bg-gray-800 transition-all peer-checked:rotate-45 peer-checked:translate-y-1.5"></span>
            <span class="block w-6 h-0.5 bg-gray-800 transition-all peer-checked:opacity-0 my-1"></span>
            <span class="block w-6 h-0.5 bg-gray-800 transition-all peer-checked:-rotate-45 peer-checked:-translate-y-1.5"></span>
        </label>

        <!-- Mobile menu -->
        <div class="hidden peer-checked:flex flex-col px-4 py-6 gap-2 bg-white shadow-md absolute top-full left-0 w-screen z-50">
            <a href="/" class="hover:text-purple-600 font-medium py-3 block">Home</a>
            <a href="#about-ceaf" class="hover:text-purple-600 font-medium py-3 block">Our Work</a>

            <!-- Your Loss dropdown -->
            <div class="relative w-full">
            <input type="checkbox" id="loss-toggle" class="hidden peer" />
            <label for="loss-toggle" class="flex justify-between items-center w-full hover:text-purple-600 font-medium py-3 cursor-pointer">
                Your Loss
                <svg class="w-6 h-4 transition-transform duration-200 peer-checked:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </label>
        <div class="hidden peer-checked:flex flex-col pl-4 mt-1 w-full bg-white shadow rounded-md z-50">
                <a href="/bereaved-children" class="py-2 hover:text-purple-200 block w-full">Bereaved Children</a>
                <a href="/bereaved-parents" class="py-2 hover:text-purple-200 block w-full">Bereaved Parents</a>
                <a href="/bereaved-siblings" class="py-2 hover:text-purple-200 block w-full">Bereaved Siblings</a>
                <a href="/bereaved-spouses" class="py-2 hover:text-purple-200 block w-full">Bereaved Spouses</a>
                <a href="/what-to-do" class="py-2 hover:text-purple-200 block w-full">What to Do & Expect</a>
            </div>
            </div>

            <a href="/register" class="hover:text-purple-600 font-medium py-3 block">Get Involved</a>
            <a href="/contact" class="hover:text-purple-600 font-medium py-3 block">Contact Us</a>
            <a href="/donate" class="bg-purple-600 text-white px-4 py-3 rounded-md font-semibold text-center hover:bg-purple-700 transition block">Donate</a>
        </div>
        </div>

    </div>
  </div>

</header>




        <!-- ================= CONTACT US ================= -->
<section id="what-to-do" class="py-24 bg-gray-50">
  <div class="max-w-4xl mx-auto px-6 text-center">
    <h1 class="text-4xl md:text-5xl font-extrabold text-purple-600 mb-6">
      What to Do & Expect
      <span class="block mt-2 w-20 h-1 bg-yellow-400 rounded-full mx-auto"></span>
        <p class="text-gray-700 text-lg md:text-xl">
        There is no recipe or timeline to follow. Your grieving process is yours to own. Take one day at a time.
      </p>
    </h1>

    @if(auth()->check() && auth()->user()->is_member)
      <p class="text-gray-700 text-lg md:text-xl mb-8">
        Step-by-step guidance for coping with loss, attending memorials, and accessing support.
      </p>

      <ul class="text-left list-disc pl-6 space-y-2 text-gray-700 mb-8">
        <li>Immediate steps after losing a loved one</li>
        <li>Preparing for memorials and ceremonies</li>
        <li>How to access counseling and support groups</li>
        <li>Follow-up and ongoing bereavement care</li>
      </ul>

      <a href="/events/register?category=guidance" class="bg-purple-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-purple-700 transition">
        Register for Guidance Session
      </a>
    @else
  <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-6">
    <p class="font-semibold">
        Bereavement support services are available <strong>only to registered members</strong>.
    </p>
    <p class="mt-2">
        Not a member? 
        <a href="/register" target="_blank" rel="noopener noreferrer" class="text-purple-600 font-semibold underline hover:text-purple-700">
            Register here
        </a>.
    </p>
    <p class="mt-1">
        Already a member? 
        <a href="/login" target="_blank" rel="noopener noreferrer" class="text-purple-600 font-semibold underline hover:text-purple-700">
            Login here
        </a>.
    </p>
</div>

    @endif
  </div>
</section>

<section id="what-to-do" class="py-24 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <!-- Intro paragraph -->
    <div class="mb-12 max-w-3xl mx-auto text-gray-700 text-lg">
      <p>Life will never be exactly the same after a loss. Every day there will be reminders that your loved one is no longer with you. Early days may feel chaotic and overwhelming, but over time, grief evolves. Be kind and patient with yourself as your needs and perspectives change. Here are some considerations to help you cope:</p>
    </div>

    <!-- Tips grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Tip 1 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Take care of yourself</h3>
        <p class="text-gray-700">Care for yourself in a gentle and nourishing way. Start small—eat, shower, rest. Over time, make conscious choices that protect and preserve your wellbeing.</p>
      </div>

      <!-- Tip 2 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Know you are not alone</h3>
        <p class="text-gray-700">You may feel isolated, but others have experienced similar losses. Seek out supportive friends, family, or local networks. Connection, even if small, can help.</p>
      </div>

      <!-- Tip 3 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Find ways to remember your loved one</h3>
        <p class="text-gray-700">Honor your loved one in meaningful ways: create keepsakes, visit meaningful places, or engage in activities they loved. Nature and rituals can also provide comfort and remembrance.</p>
      </div>

      <!-- Tip 4 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Reemerge at your own pace</h3>
        <p class="text-gray-700">Social settings, work, or public events may feel overwhelming. Go at your own pace and reengage with the world gradually, respecting your emotional limits.</p>
      </div>

      <!-- Tip 5 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Navigate society and find support</h3>
        <p class="text-gray-700">Ask for help, seek resources, therapy, or support groups. Local and online support networks can provide guidance and connection during your grief journey.</p>
      </div>

      <!-- Tip 6 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Connect with others when ready</h3>
        <p class="text-gray-700">Engage with friends, family, or community support when you feel ready. Meaningful connections help diminish grief’s toll.</p>
      </div>

      <!-- Tip 7 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Share your story</h3>
        <p class="text-gray-700">Share your experiences with trusted friends, family, or supportive communities. Writing, talking, or storytelling can aid healing and connection.</p>
      </div>

      <!-- Tip 8 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Expect reminders</h3>
        <p class="text-gray-700">Certain moments—songs, smells, or places—can trigger memories. Prepare for these and give yourself permission to feel without judgment.</p>
      </div>

      <!-- Tip 9 -->
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold text-purple-600 mb-2">Life changes</h3>
        <p class="text-gray-700">Life will continue to evolve. Be patient with yourself as you adapt to new routines, traditions, and memories while honoring your loved one.</p>
      </div>
    </div>

    <!-- Closing message -->
    <div class="mt-12 text-center text-purple-600 rounded-lg bg-yellow-400 text-lg">
      <p>Remember: grief is a personal journey. Be kind, patient, and compassionate with yourself as you navigate this path.</p>
    </div>
  </div>
</section>

   <!-- ================= FFooter 1 ================= -->
        <section
            class="relative w-full bg-cover bg-center py-16"
            style="background-image: url('{{ asset('images/footer-img.jpg') }}');"
        >
            <div class="absolute inset-0 bg-black/40"></div>

            <!-- content width preserved -->
            <div class="relative max-w-2xl px-6 lg:max-w-7xl mx-auto">
                <div class="flex flex-wrap">

                    <div class="hidden md:block md:w-1/2 lg:w-1/2"></div>

                    <div class="w-full md:w-1/2 lg:w-1/2 px-4 text-white">
                        <div class="inner">
                            <h5 class="text-sm font-semibold uppercase mb-2">
                                Get in touch
                            </h5>
                            <h3 class="text-3xl font-bold text-yellow-400 mb-4 relative inline-block">
                            Contact us today
                            <span class="block w-16 h-1 bg-yellow-400 mt-2"></span>
                            </h3>


                            <p class="mb-6">
                                For more information about the Carolina East Africa Foundation, contact us today on:
                            </p>

                            <div class="mb-4">
                                <h4 class="text-lg mb-2">UK callers:</h4>
                                <a href="tel:+448000246121" class="font-bold">
                                    0800 0246 121
                                </a>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-lg mb-2">International callers:</h4>
                                <a href="tel:+01916563201" class="font-bold">
                                    0191 656 3201
                                </a>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-lg mb-2">Email:</h4>
                                <a href="mailto:info@ceaf.org" class="font-bold">
                                    info@ceaf.org
                                </a>
                            </div>

                            <p>Or contact us via our social media links below.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ================= FOOTER ================= -->
        <footer class="bg-gray-100 dark:bg-gray-900 text-sm text-gray-800 dark:text-white/70">
            <div class="w-full mx-auto px-6 py-16">

                <!-- Top footer -->
                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">

                    <!-- Contact -->
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                            Contact Us
                        </h3>

                        <p class="mb-2">
                            <a
                                href="mailto:carolinaeastafrica@ceaf.org"
                                class="text-teal-700 hover:underline dark:text-teal-400"
                            >
                                info@ceaf.org | carolinaeastafrica@gmail.com
                            </a>
                        </p>

                        <p class="mb-2">
                            <strong>East Africa:</strong><br>
                            <a href="tel:+254700000000" class="hover:underline">
                                +254 700 000 000
                            </a>
                        </p>

                        <p class="mb-2">
                            <strong>International:</strong><br>
                            <a href="tel:+12025550000" class="hover:underline">
                                +1 202 555 0000
                            </a>
                        </p>

                        <p class="mt-3 text-xs text-gray-600 dark:text-white/50">
                            *Call charges may vary by network provider.
                        </p>
                    </div>

                    <!-- Address -->
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                            Our Address
                        </h3>

                        <p class="mb-4 leading-relaxed">
                            Carolina East Africa Foundation (CEAF)<br>
                            Nairobi, Kenya
                        </p>

                        <h4 class="mb-1 font-medium text-gray-900 dark:text-white">
                            Registered Office
                        </h4>

                        <p class="leading-relaxed">
                            United States of America
                        </p>

                        <p class="mt-3">
                            <strong>Registration No:</strong> CEAF-0001
                        </p>
                    </div>

                    <!-- Office Hours -->
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                            Office Hours
                        </h3>

                        <p class="mb-4 leading-relaxed">
                            Monday – Friday: 9:00am – 5:00pm<br>
                            Saturday & Sunday: Closed
                        </p>

                        <h4 class="mb-2 font-medium text-gray-900 dark:text-white">
                            Support Our Mission
                        </h4>

                        <p class="mb-4">
                            Empowering communities across East Africa.
                        </p>

                        <a
                            href="{{ url('/donate') }}"
                            class="inline-block rounded-md bg-teal-700 px-5 py-2 text-sm font-semibold text-white transition hover:bg-teal-800"
                        >
                            Donate
                        </a>
                    </div>

                    <!-- Social Media -->
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                            Connect With Us
                        </h3>

                        <div class="flex gap-4">
                            <!-- Facebook -->
                            <a
                                href="#"
                                aria-label="Facebook"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-700 text-white hover:bg-teal-800"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path d="M22.675 0h-21.35C.597 0 0 .597 0 1.326v21.348C0 23.403.597 24 1.326 24H12.82v-9.294H9.692V11.41h3.128V8.797c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24h-1.918c-1.505 0-1.797.716-1.797 1.765v2.314h3.587l-.467 3.296h-3.12V24h6.116C23.403 24 24 23.403 24 22.674V1.326C24 .597 23.403 0 22.675 0z"/>
                                </svg>
                            </a>

                            <!-- X / Twitter -->
                            <a
                                href="#"
                                aria-label="X (Twitter)"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-700 text-white hover:bg-teal-800"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path d="M18.244 2.25h3.308l-7.227 8.26L22.827 21.75h-6.63l-5.192-6.795-5.944 6.795H1.75l7.73-8.835L1.5 2.25h6.797l4.694 6.142L18.244 2.25z"/>
                                </svg>
                            </a>

                            <!-- Instagram -->
                            <a
                                href="#"
                                aria-label="Instagram"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-700 text-white hover:bg-teal-800"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path d="M7.5 2C4.462 2 2 4.462 2 7.5v9C2 19.538 4.462 22 7.5 22h9c3.038 0 5.5-2.462 5.5-5.5v-9C22 4.462 19.538 2 16.5 2h-9zm9 1.5c2.21 0 4 1.79 4 4v9c0 2.21-1.79 4-4 4h-9c-2.21 0-4-1.79-4-4v-9c0-2.21 1.79-4 4-4h9z"/>
                                    <path d="M12 7a5 5 0 100 10 5 5 0 000-10zm0 1.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7z"/>
                                    <circle cx="17.5" cy="6.5" r="1.5"/>
                                </svg>
                            </a>

                            <!-- LinkedIn -->
                            <a
                                href="#"
                                aria-label="LinkedIn"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-700 text-white hover:bg-teal-800"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="h-5 w-5"
                                >
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.6 0 4.266 2.37 4.266 5.455v6.286zM5.337 7.433a2.062 2.062 0 110-4.125 2.062 2.062 0 010 4.125zM6.814 20.452H3.861V9h2.953v11.452z"/>
                                </svg>
                            </a>

                        </div>
                    </div>

                </div>

                <!-- Divider -->
                <div class="my-10 border-t border-gray-300 dark:border-white/10"></div>

                <!-- Bottom footer -->
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <ul class="flex flex-wrap gap-4 text-sm">
                        <li><a href="{{ url('/terms') }}" class="hover:underline">Terms & Conditions</a></li>
                        <li><a href="{{ url('/privacy-policy') }}" class="hover:underline">Privacy Policy</a></li>
                        <li><a href="{{ url('/complaints') }}" class="hover:underline">Complaints Policy</a></li>
                    </ul>

                    <p class="text-xs text-gray-600 dark:text-white/50">
                        © {{ date('Y') }} Carolina East Africa Foundation
                    </p>
                </div>

            </div>
        </footer>
        <!-- ================= END FOOTER ================= -->

    </div>
</div>
</body>
</html>
