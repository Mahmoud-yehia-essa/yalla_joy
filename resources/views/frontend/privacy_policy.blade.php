<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>فيك تحدي - Privacy Policy</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  
  <style>
    body {
      font-family: 'Plus Jakarta Sans', 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .custom-gradient {
      background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }
    .card-shadow {
      box-shadow: 0 4px 20px -2px rgba(50, 50, 93, 0.05), 0 2px 10px -1px rgba(0, 0, 0, 0.03);
    }
    .nav-active {
      color: #4f46e5;
      border-left-color: #4f46e5;
      font-weight: 600;
      background-color: rgba(79, 70, 229, 0.05);
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-900">

  <!-- Top Hero Header -->
  <header class="custom-gradient text-white py-12 px-4 relative overflow-hidden shadow-lg">
    <!-- Abstract background decorations -->
    <div class="absolute inset-0 opacity-10">
      <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
        <path d="M0,100 C30,40 70,60 100,0 L100,100 Z" fill="white"></path>
      </svg>
    </div>
    <div class="absolute -top-10 -left-10 w-40 h-40 bg-white rounded-full opacity-10 filter blur-xl"></div>
    <div class="absolute -bottom-10 -right-10 w-60 h-60 bg-indigo-300 rounded-full opacity-20 filter blur-2xl"></div>

    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-between relative z-10">
      <div class="flex items-center space-x-6 mb-6 md:mb-0">
        <!-- Logo container -->
        <div class="bg-white p-3 rounded-2xl shadow-md transform hover:rotate-3 transition-transform duration-300">
          <img src="{{ asset('backend/assets/images/login-images/logo_tahadi.png') }}" class="h-16 w-auto object-contain" alt="فيك تحدي Logo" onerror="this.onerror=null; this.src='https://placehold.co/120x60/4f46e5/ffffff?text=فيك+تحدي';">
        </div>
        <div>
          <div class="flex items-center space-x-2">
            <span class="bg-indigo-500/30 text-indigo-100 text-xs px-2.5 py-1 rounded-full font-semibold tracking-wider uppercase">Official Document</span>
            <span class="bg-white/20 text-white text-xs px-2.5 py-1 rounded-full font-semibold tracking-wider">v2026.1</span>
          </div>
          <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mt-2 flex items-center gap-2">
            <span>Privacy Policy</span>
            <span class="font-cairo text-2xl font-bold bg-white/10 px-2 py-0.5 rounded-lg text-yellow-300">فيك تحدي</span>
          </h1>
          <p class="text-indigo-100 text-sm mt-1">Effective Date: June 22, 2026</p>
        </div>
      </div>
      <div class="flex flex-col items-end text-right md:text-left md:items-start">
        <p class="text-indigo-100 text-xs uppercase tracking-widest font-semibold">Service Provider</p>
        <p class="text-lg font-bold text-yellow-300">H J PIK</p>
        <a href="mailto:husjowhar@gmail.com" class="text-sm text-white hover:text-yellow-200 transition-colors flex items-center gap-1.5 mt-1 underline">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          husjowhar@gmail.com
        </a>
      </div>
    </div>
  </header>

  <!-- Main Layout Container -->
  <main class="max-w-5xl mx-auto px-4 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
      
      <!-- Sticky Navigation Sidebar for Desktop -->
      <aside class="hidden lg:block lg:col-span-4 sticky top-6 bg-white rounded-2xl p-5 border border-slate-100 card-shadow">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-3 mb-4">Table of Contents</h3>
        <nav class="space-y-1">
          <a href="#intro" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Introduction</span>
          </a>
          <a href="#info-collect" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Information Collection & Use</span>
          </a>
          <a href="#cookies" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Cookies & Tracking</span>
          </a>
          <a href="#rights" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Your Rights & CCPA/CPRA</span>
          </a>
          <a href="#third-party" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Third Party & Data Transfers</span>
          </a>
          <a href="#retention" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Data Retention & Opt-Out</span>
          </a>
          <a href="#children" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Children & Security</span>
          </a>
          <a href="#changes" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Changes & Consent</span>
          </a>
          <a href="#contact" class="nav-item flex items-center space-x-3 px-3 py-2 text-sm text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border-l-2 border-transparent transition-all">
            <span>Contact Us</span>
          </a>
        </nav>
      </aside>

      <!-- Content Area -->
      <div class="lg:col-span-8 space-y-6">
        
        <!-- Welcome Card -->
        <section id="intro" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Introduction</h2>
          </div>
          <p class="text-slate-600 leading-relaxed">
            This privacy policy applies to the <strong class="text-indigo-600 font-cairo">فيك تحدي</strong> app for mobile devices, together with any related services operated by <strong>H J PIK</strong> (collectively, the "Application"). <strong>H J PIK</strong> is hereby referred to as the "Service Provider".
          </p>
        </section>

        <!-- Information Collection Card -->
        <section id="info-collect" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Information Collection and Use</h2>
          </div>
          <p class="text-slate-600 leading-relaxed mb-4">
            The Application collects information when you download and use it. This information may include parameters such as:
          </p>
          <ul class="space-y-3">
            <li class="flex items-start space-x-3 text-slate-600">
              <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span>Your device's Internet Protocol (IP) address.</span>
            </li>
            <li class="flex items-start space-x-3 text-slate-600">
              <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span>The pages of the Application that you visit, the time and date of your visit, and the time spent on those pages.</span>
            </li>
            <li class="flex items-start space-x-3 text-slate-600">
              <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span>The overall duration and frequency of using the Application.</span>
            </li>
            <li class="flex items-start space-x-3 text-slate-600">
              <svg class="w-5 h-5 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <span>The specific mobile operating system you use.</span>
            </li>
          </ul>
          <p class="text-slate-600 leading-relaxed mt-4">
            The Service Provider may use the information you provide to send important information, required notices, and, where permitted by law, marketing communications.
          </p>
          <p class="text-slate-600 leading-relaxed mt-3">
            For a better experience while using the Application, the Service Provider may require you to provide certain personally identifiable information. The information the Service Provider requests will be retained and used as described in this privacy policy.
          </p>
        </section>

        <!-- Cookies Card -->
        <section id="cookies" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 009 11.75M3.75 6.58c.984-1.816 2.602-3.134 4.5-3.724M12 4.195a13.916 13.916 0 013.25 7.555m-3.25-7.555a13.916 13.916 0 00-3.25 7.555M12 4.195a13.916 13.916 0 013.25 7.555M12 11c.88 0 1.737.086 2.569.25m-2.57 0c-.88 0-1.737-.086-2.57-.25m8.584-2.43c.983 1.817 1.132 4.157.402 6.116m-3.239-2.041A13.916 13.916 0 0012 11.75M4.5 18a13.916 13.916 0 00a9.23 9.23 0 00-1.792-3.003"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Cookies and Tracking Technologies</h2>
          </div>
          <p class="text-slate-600 leading-relaxed">
            The Application or its third-party SDKs may use cookies, SDKs, pixels, and similar technologies to support functionality, analytics, or service delivery. Where required by applicable law, the Service Provider will obtain consent before using non-essential tracking technologies.
          </p>
        </section>

        <!-- Your Rights & California Rights Card -->
        <section id="rights" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12a3 3 0 11-6 0 3 3 0 016 0zm6 2a6 6 0 016-6v1a6 6 0 01-6 6h-1v-6h1z"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Your Rights</h2>
          </div>
          
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-slate-800 mb-2">General Rights</h3>
              <p class="text-slate-600 leading-relaxed">
                You may request access to, correction of, or deletion of your personal data held by the Service Provider. To exercise these rights, or to withdraw consent where processing is based on consent, contact the Service Provider at <a href="mailto:husjowhar@gmail.com" class="text-indigo-600 hover:text-indigo-800 font-medium underline">husjowhar@gmail.com</a>.
              </p>
            </div>

            <div class="border-t border-slate-100 pt-6">
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Your California Privacy Rights (CCPA/CPRA)</h3>
              <p class="text-slate-600 leading-relaxed mb-4">
                If you are a California resident, you have specific rights regarding your personal information, including:
              </p>
              <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <li class="flex items-center space-x-2 text-slate-600 bg-slate-50 p-3 rounded-lg">
                  <span class="text-indigo-500 font-bold">✓</span>
                  <span>Right to know what is collected</span>
                </li>
                <li class="flex items-center space-x-2 text-slate-600 bg-slate-50 p-3 rounded-lg">
                  <span class="text-indigo-500 font-bold">✓</span>
                  <span>Right to delete personal information</span>
                </li>
                <li class="flex items-center space-x-2 text-slate-600 bg-slate-50 p-3 rounded-lg">
                  <span class="text-indigo-500 font-bold">✓</span>
                  <span>Right to opt out of sale/sharing</span>
                </li>
                <li class="flex items-center space-x-2 text-slate-600 bg-slate-50 p-3 rounded-lg">
                  <span class="text-indigo-500 font-bold">✓</span>
                  <span>Right to non-discrimination</span>
                </li>
              </ul>
              <p class="text-slate-600 leading-relaxed mt-4">
                To exercise your CCPA/CPRA rights, contact the Service Provider at <a href="mailto:husjowhar@gmail.com" class="text-indigo-600 hover:text-indigo-800 font-medium underline">husjowhar@gmail.com</a>.
              </p>
            </div>
          </div>
        </section>

        <!-- Third Party Access & International Data Transfers Card -->
        <section id="third-party" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h2.945M11 20.955V19a2 2 0 00-2-2h-1a2 2 0 01-2-2v-1a2 2 0 00-2-2H2.05M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Third Party Access & Transfers</h2>
          </div>
          
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Third Party Access</h3>
              <p class="text-slate-600 leading-relaxed mb-3">
                Only aggregated, anonymized data is periodically transmitted to external services to aid the Service Provider in improving the Application and their service. The Service Provider may share your information with third parties in the ways that are described in this privacy statement.
              </p>
              <p class="text-slate-600 leading-relaxed mb-2">
                The Service Provider may disclose User Provided and Automatically Collected Information:
              </p>
              <ul class="space-y-2 pl-2">
                <li class="flex items-start space-x-3 text-slate-600">
                  <span class="text-indigo-500 font-bold">•</span>
                  <span>As required by law, such as to comply with a subpoena, or similar legal process.</span>
                </li>
                <li class="flex items-start space-x-3 text-slate-600">
                  <span class="text-indigo-500 font-bold">•</span>
                  <span>When they believe in good faith that disclosure is necessary to protect their rights, protect your safety or the safety of others, investigate fraud, or respond to a government request.</span>
                </li>
                <li class="flex items-start space-x-3 text-slate-600">
                  <span class="text-indigo-500 font-bold">•</span>
                  <span>With trusted services providers who work on their behalf, do not have an independent use of the information the Service Provider discloses to them, and have agreed to adhere to the rules set forth in this privacy statement.</span>
                </li>
              </ul>
            </div>

            <div class="border-t border-slate-100 pt-6">
              <h3 class="text-lg font-semibold text-slate-800 mb-2">International Data Transfers</h3>
              <p class="text-slate-600 leading-relaxed mb-4">
                The Service Provider or its third-party service providers may transfer personal data to countries outside your country of residence, including outside the European Economic Area (EEA). Where applicable law requires safeguards for international transfers, the Service Provider will use appropriate mechanisms:
              </p>
              <ul class="space-y-3">
                <li class="flex items-start space-x-3 text-slate-600">
                  <div class="bg-indigo-50 p-1 rounded-md text-indigo-600 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                  </div>
                  <span><strong>Standard Contractual Clauses (SCCs)</strong> approved by the European Commission.</span>
                </li>
                <li class="flex items-start space-x-3 text-slate-600">
                  <div class="bg-indigo-50 p-1 rounded-md text-indigo-600 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                  </div>
                  <span><strong>Adequacy decisions</strong> or other legally recognized transfer mechanisms.</span>
                </li>
                <li class="flex items-start space-x-3 text-slate-600">
                  <div class="bg-indigo-50 p-1 rounded-md text-indigo-600 mt-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                  </div>
                  <span><strong>Your explicit consent</strong>, where required and legally permitted.</span>
                </li>
              </ul>
              <p class="text-slate-600 leading-relaxed mt-4">
                Data protection laws in other countries may differ from those in your jurisdiction. Where required by law, the Service Provider will apply appropriate safeguards and obtain any consent required for the transfer.
              </p>
            </div>
          </div>
        </section>

        <!-- Data Retention Policy & Opt-Out Card -->
        <section id="retention" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Retention & Opt-Out</h2>
          </div>
          
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Data Retention Policy</h3>
              <p class="text-slate-600 leading-relaxed mb-4">
                The Service Provider retains personal data based on its necessity for the stated purposes:
              </p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                  <p class="text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">User Provided Data</p>
                  <p class="text-slate-700 font-medium">Duration of application use + 12 months</p>
                  <p class="text-xs text-slate-500 mt-2">Unless longer retention is required by law.</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                  <p class="text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Automatically Collected Data</p>
                  <p class="text-slate-700 font-medium">Up to 24 months from collection</p>
                  <p class="text-xs text-slate-500 mt-2">Unless longer retention is required for legal compliance.</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                  <p class="text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Aggregated & Anonymized Data</p>
                  <p class="text-slate-700 font-medium">Retained indefinitely</p>
                  <p class="text-xs text-slate-500 mt-2">It no longer identifies you personally.</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                  <p class="text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Required for Legal Compliance</p>
                  <p class="text-slate-700 font-medium">As long as required by law</p>
                  <p class="text-xs text-slate-500 mt-2">Subject to any legal obligation to retain it.</p>
                </div>
              </div>
              <p class="text-slate-600 leading-relaxed mt-4">
                You may request deletion of your personal data, subject to any legal obligation to retain it. If you want the Service Provider to delete User Provided Data submitted through the Application, please contact them at <a href="mailto:husjowhar@gmail.com" class="text-indigo-600 hover:text-indigo-800 font-medium underline">husjowhar@gmail.com</a>. Please note that some User Provided Data may be required for the Application to function properly.
              </p>
            </div>

            <div class="border-t border-slate-100 pt-6">
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Opt-Out Rights</h3>
              <p class="text-slate-600 leading-relaxed">
                You can stop further collection of information from your mobile device easily by uninstalling the Application. Uninstalling will stop the Application from collecting data from your device, but it does not automatically delete information that has already been transmitted to the Service Provider or to third parties.
              </p>
              <p class="text-slate-600 leading-relaxed mt-3">
                To request deletion of your personal data, to withdraw consent, or to exercise any of your rights, contact the Service Provider at <a href="mailto:husjowhar@gmail.com" class="text-indigo-600 hover:text-indigo-800 font-medium underline">husjowhar@gmail.com</a>.
              </p>
            </div>
          </div>
        </section>

        <!-- Children & Security Card -->
        <section id="children" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Children & Security</h2>
          </div>
          
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Children's Privacy</h3>
              <p class="text-slate-600 leading-relaxed">
                The Application is not intended for children under 16 years of age, or such higher age as required by applicable law. The Service Provider does not knowingly solicit data from children or market the Application to them.
              </p>
              <p class="text-slate-600 leading-relaxed mt-3">
                The Service Provider does not knowingly collect personally identifiable information from children. The Service Provider encourages all children to never submit any personally identifiable information through the Application and/or Services. The Service Provider encourages parents and legal guardians to monitor their children's Internet usage and to help enforce this Policy by instructing their children never to provide personally identifiable information through the Application and/or Services without their permission. If you have reason to believe that a child has provided personally identifiable information to the Service Provider through the Application and/or Services, please contact the Service Provider (<a href="mailto:husjowhar@gmail.com" class="text-indigo-600 hover:text-indigo-800 underline">husjowhar@gmail.com</a>) so that they will be able to take the necessary actions. If you are under 16 years of age, your parent or guardian must provide consent on your behalf where permitted by law.
              </p>
            </div>

            <div class="border-t border-slate-100 pt-6">
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Security Measures</h3>
              <p class="text-slate-600 leading-relaxed">
                The Service Provider is concerned about safeguarding the confidentiality of your information. The Service Provider provides physical, electronic, and procedural safeguards to protect information the Service Provider processes and maintains.
              </p>
              <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-xl mt-4">
                <h4 class="text-sm font-bold text-indigo-900 uppercase tracking-wide">Data Breach Notification</h4>
                <p class="text-sm text-indigo-800 mt-1 leading-relaxed">
                  If a data breach occurs that affects your personal data, the Service Provider will notify you in accordance with applicable legal requirements, including, where required, providing information about the nature of the breach and the steps being taken to address it.
                </p>
              </div>
            </div>
          </div>
        </section>

        <!-- Changes & Consent Card -->
        <section id="changes" class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 card-shadow scroll-mt-6">
          <div class="flex items-center space-x-3 mb-4">
            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.22m-8.91 3.51a3.003 3.003 0 01-1.89-1.89"></path></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Changes & Consent</h2>
          </div>
          
          <div class="space-y-6">
            <div>
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Policy Updates</h3>
              <p class="text-slate-600 leading-relaxed">
                The Service Provider may update this Privacy Policy from time to time. The Service Provider will notify you of material changes by posting the updated Privacy Policy with an effective date. Where required by law, the Service Provider will seek your consent to material changes before they take effect.
              </p>
              <p class="text-slate-600 leading-relaxed mt-3">
                Previous versions of this Privacy Policy will be maintained and made available upon request by contacting the Service Provider at <a href="mailto:husjowhar@gmail.com" class="text-indigo-600 hover:text-indigo-800 underline">husjowhar@gmail.com</a>.
              </p>
            </div>

            <div class="border-t border-slate-100 pt-6">
              <h3 class="text-lg font-semibold text-slate-800 mb-2">Your Consent</h3>
              <p class="text-slate-600 leading-relaxed">
                Where processing is based on consent, you provide that consent by affirmatively opting in to the relevant feature or action. You may withdraw consent at any time without affecting processing carried out before withdrawal. Processing based on other lawful bases is carried out as described above.
              </p>
            </div>
          </div>
        </section>

        <!-- Contact Us Card -->
        <section id="contact" class="bg-indigo-900 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl scroll-mt-6">
          <div class="absolute inset-0 bg-gradient-to-br from-indigo-800 via-indigo-900 to-purple-950 opacity-95"></div>
          
          <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
              <div class="flex items-center space-x-2 mb-2">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 animate-pulse"></span>
                <span class="text-yellow-300 font-bold uppercase tracking-wider text-xs">Questions or Inquiries?</span>
              </div>
              <h2 class="text-2xl font-bold mb-2">Contact the Service Provider</h2>
              <p class="text-indigo-200 text-sm max-w-md">
                If you have any questions regarding privacy while using the Application, or have questions about the practices, please reach out directly.
              </p>
            </div>
            
            <a href="mailto:husjowhar@gmail.com" class="w-full md:w-auto bg-yellow-400 hover:bg-yellow-300 text-slate-900 font-bold px-8 py-4 rounded-2xl shadow-lg transition-transform hover:-translate-y-0.5 text-center flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
              <span>husjowhar@gmail.com</span>
            </a>
          </div>
        </section>

      </div>
    </div>
  </main>

  <!-- Sticky Footer -->
  <footer class="bg-white border-t border-slate-100 mt-20 py-8 px-4 text-center">
    <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400">
      <div>
        <p>&copy; 2026 فيك تحدي. All rights reserved.</p>
        <p class="mt-1">Operated by H J PIK.</p>
      </div>
      <div class="mt-4 sm:mt-0 flex items-center gap-2 text-indigo-400">
        <span>Generated by App Privacy Policy Generator</span>
      </div>
    </div>
  </footer>

  <!-- Simple JavaScript for sticky TOC highlight -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sections = document.querySelectorAll('section');
      const navItems = document.querySelectorAll('.nav-item');

      // Set active helper
      function activateNav() {
        let index = sections.length;

        while(--index && window.scrollY + 100 < sections[index].offsetTop) {}
        
        navItems.forEach((item) => item.classList.remove('nav-active'));
        if(navItems[index]) {
          navItems[index].classList.add('nav-active');
        }
      }

      activateNav();
      window.addEventListener('scroll', activateNav);
    });
  </script>

</body>
</html>
