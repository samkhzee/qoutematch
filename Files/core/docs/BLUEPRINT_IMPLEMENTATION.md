# QuoteMatch — Complete Blueprint Implementation Plan

Source document: `Complete Blueprint for Service and Product Comparison Website` (38 pages)

---

## Development order

| Phase | Modules | Blueprint sections |
|-------|---------|-------------------|
| 1 | Foundation & branding | §1–4, §34, §42.1 |
| 2 | Launch categories | §5, §10, §28 |
| 3 | Registration & profiles | §12, §42.2–3 |
| 4 | Dynamic **request** forms | §5 fields, §11, §24 |
| 5 | Dynamic **quote** forms | §5 quote fields, §11, §42.7 |
| 6 | Provider matching | §25, §42.9 |
| 7 | Quote comparison | §9, §35, §42.11 |
| 8 | Messaging & notifications | §15–16, §42.12 |
| 9 | Verification badges | §13, §42.15 |
| 10 | Reviews (structured) | §14, §42.14 |
| 11 | Admin form builder | §11, §28 |
| 12 | Monetisation (Phase 2) | §17, §38 Milestone 6 |
| 13 | Admin dashboard extensions | §6.4, §31–32 |
| 14 | Notifications hub | §16, §42 #17 |
| 15 | Public SEO & legal | §27, §30, §33 |
| 16 | Monetisation go-live | §17, §38 Milestone 6 |
| 17 | MVP polish & mobile | §39, §42 #18 |
| 18 | React dashboard migration | Conversation, projects, bids, disputes |
| 19 | React account pages | Support, profile, withdraw, KYC, transactions, 2FA |
| 20 | Final React migration | Auth reset/verify, deposits, trial tasks, blueprint closure |
| 21 | Gateway checkout React | Stripe/PayPal/etc. via Shared/GatewayCheckout |
| 22 | Quote intelligence | Summed freight totals, compare breakdown |
| 23 | Location matching v2 | SeoLocation + postcode-aware JobMatchingService |
| 24 | Admin React hub | Marketplace dashboard + disputes (Inertia) |
| 25 | Phase 2 closure | verify-blueprint modules 21–25, production checklist |
| 26 | Admin React jobs & quotes | Job lists, detail, bid moderation |
| 27 | Admin verifications & reviews | Provider docs + structured review moderation |
| 28 | Match score UI | Location/service match bars on job browse |
| 29 | Provider approval queue | Admin pending provider React list |
| 30 | Phase 3 closure | verify-blueprint modules 26–30, production checklist |
| 31 | Admin React categories | Categories, subcategories, skills |
| 32 | Admin React provider lists | All provider list scopes |
| 33 | Admin React provider detail | Provider detail hub |
| 34 | Admin React buyers | Customer list + detail |
| 35 | Admin React monetisation | Settings, credit packages, plans |
| 36 | Admin React finance ops | Deposits + withdrawals |
| 37 | Admin React projects | Project manager index + detail |
| 38 | Admin React marketplace forms | Forms index (edit stays Blade builder) |
| 39 | Postcode outcode matching v3 | UK outcode prefix match + UI badge |
| 40 | Gateway checkout polish | Gateway name/metadata in React shell |
| 41 | Admin React support | Support ticket list + reply |
| 42 | Admin React trial tasks | Trial task list + detail |
| 43 | Admin React reports | Transaction log + withdraw methods |
| 44 | Admin bridge audit | Marketplace controllers migration audit |
| 45 | Phase 4 closure | verify-blueprint modules 31–45, production checklist |

---

## Database schema (Blueprint §22 + extensions)

| Table / column | Purpose | Status |
|----------------|---------|--------|
| `categories.request_form_id` | Category request form | ✅ Module 4 |
| `categories.quote_form_id` | Category quote form | ✅ Module 5 |
| `jobs.request_data` (JSON) | Structured request answers | ✅ Module 4 |
| `bids.quote_data` (JSON) | Structured quote answers | ✅ Module 5 |
| `bids.revision_requested_at`, `bids.revision_note` | Quote revision requests | ✅ Module 7 |
| `conversations.job_id`, `conversations.bid_id` | Quote-linked messaging | ✅ Module 8 |
| `provider_verifications` | Insurance, company, licence badges | ✅ Module 9 |
| `reviews.scores`, `reviews.moderated_*` | Structured review dimensions + moderation | ✅ Module 10 |
| `users.lead_credits` | Provider lead credit balance | ✅ Module 12 |
| `lead_credit_packages` | Purchasable credit bundles | ✅ Module 12 |
| `subscription_plans`, `provider_subscriptions` | Provider subscription plans | ✅ Module 12 |
| `lead_credit_logs` | Credit purchase & usage audit trail | ✅ Module 12 |
| `provider_subscriptions.expiry_cron` | Subscription expiry + renewal alerts | ✅ Module 16 |
| `disputes` | Structured dispute records (MVP) | ✅ Module 13 |
| `seo_locations` | UK location SEO landing pages | ✅ Module 15 |

---

## API / routes (key endpoints)

| Route | Method | Purpose |
|-------|--------|---------|
| `/buyer/job/post/*` | GET/POST | Post request (3 steps) |
| `/buyer/job/post/bids/{jobId}` | GET | Compare quotes |
| `/buyer/job/post/bids/{bidId}/shortlist` | POST | Shortlist quote |
| `/buyer/job/post/bids/{bidId}/reject` | POST | Reject quote |
| `/buyer/job/post/bids/{bidId}/revision` | POST | Request quote revision |
| `/buyer/conversation/bid-chat/{bidId}` | GET | Quote-gated chat |
| `/buyer/job/post/hire-talent/{bidId}` | POST | Accept quote |
| `/freelancer/bid/store/{jobId}` | POST | Submit structured quote |
| `/freelancer/verification` | GET/POST | Provider verification uploads |
| `/admin/provider-verifications` | GET/POST | Admin review insurance/company/licence |
| `/admin/reviews` | GET/POST | Admin review moderation |
| `/admin/marketplace-forms` | GET/POST | Admin request & quote form builder |
| `/admin/monetisation/settings` | GET/POST | Lead credits & subscription settings |
| `/admin/monetisation/packages` | GET/POST | Credit package management |
| `/admin/monetisation/plans` | GET/POST | Subscription plan management |
| `/admin/marketplace/dashboard` | GET | QuoteMatch analytics hub |
| `/admin/disputes` | GET/POST | Dispute moderation |
| `/admin/bids/detail/{id}` | GET | Structured quote detail |
| `/freelancer/lead-credits` | GET | Provider credits & plans (when enabled) |
| `/freelancer/notifications` | GET | Provider notification history |
| `/buyer/notifications` | GET | Buyer notification history |
| `/locations` | GET | UK service locations index |
| `/locations/{slug}` | GET | Location landing page |
| `/services/{category}/in/{location}` | GET | Category + location SEO page |
| `/sitemap.xml` | GET | Dynamic XML sitemap |
| `/robots.txt` | GET | Search engine robots file |
| `/policy/privacy-policy` | GET | Privacy policy (GDPR) |
| `/policy/customer-terms` | GET | Customer terms |
| `/policy/provider-terms` | GET | Provider terms |
| `/freelancer/disputes` | GET | Provider dispute list |
| `/buyer/disputes` | GET | Buyer dispute list |
| `/admin/category/index` | GET/POST | Categories + form assignment |

---

## §42 First version build — checklist

| # | Requirement | Status | Implementation |
|---|-------------|--------|----------------|
| 1 | Public website | ✅ | `apply-module-1.php`, React public pages |
| 2 | Customer registration | ✅ | `Buyer/Auth/Register.jsx` |
| 3 | Provider registration | ✅ | `User/Auth/Register.jsx` |
| 4 | Admin login | ✅ | `Admin/Auth/Login.jsx` |
| 5 | Category management | ✅ | `apply-module-2.php`, admin categories |
| 6 | Dynamic request forms | ✅ | Module 4, `RequestFormService` |
| 7 | Dynamic quote forms | ✅ | Module 5, `apply-module-5.php` |
| 8 | Customer request posting | ✅ | `Buyer/Job/JobDetails.jsx` flow |
| 9 | Provider matching | ✅ | `JobMatchingService`, `JobExploreController` |
| 10 | Quote submission | ✅ | `BidController`, `JobDetails.jsx` |
| 11 | Quote comparison | ✅ | `CompareQuotes.jsx`, filters/sort, shortlist, reject, revision |
| 12 | Messaging | ✅ | `QuoteMessagingService`, `MessageSanitizer`, bid-linked chat |
| 13 | Provider profile | ✅ | React profile steps + verification uploads |
| 14 | Reviews | ✅ | Multi-dimension scores, admin moderation, compare/profile display |
| 15 | Verification | ✅ | KYC + provider approval + insurance/company/licence badges |
| 16 | Admin dashboard | ✅ | Blade admin (fixed CSS rendering) |
| 17 | Email notifications | ✅ | Shortlist, revision, chat templates via `apply-module-7-8.php` |
| 18 | Mobile responsive | ✅ | Module 17 dashboard + public CSS polish |
| 19 | SEO location pages | ✅ | Module 15 location + category/location URLs |
| 20 | GDPR & legal pages | ✅ | Privacy, customer/provider terms, cookie consent |

---

## §39 MVP acceptance criteria

| Criterion | Status |
|-----------|--------|
| Customer register & post request | ✅ |
| Provider register & complete profile | ✅ Profile steps + verification page |
| Admin approve provider | ✅ |
| Provider view **matching** requests | ✅ |
| Provider submit **structured** quote | ✅ |
| Customer **compare** quotes side-by-side | ✅ |
| Customer message provider | ✅ Quote-gated; contact details masked until accept |
| Customer accept quote | ✅ (hire flow) |
| Customer leave review | ✅ Structured 4-dimension review |
| Admin manage categories, users, requests, quotes | ✅ Categories + form builder + marketplace hub |
| Admin dispute moderation & analytics | ✅ Module 13 |
| Mobile responsive | ✅ |
| Basic notifications | ✅ Shortlist, revision, chat, disputes, credits, deadlines + in-app history |
| Secure file uploads | ✅ Request/quote file fields |

---

## Setup & apply scripts

```bash
# From Files/core
php artisan migrate
php scripts/apply-module-1.php
php scripts/apply-module-2.php
php scripts/apply-module-3.php
php scripts/apply-module-4.php
php scripts/apply-module-5.php
php scripts/apply-module-7-8.php
php scripts/apply-module-9.php
php scripts/apply-module-10.php
php scripts/apply-module-11.php
php scripts/apply-module-12.php
php scripts/apply-module-13.php
php scripts/apply-module-14.php
php scripts/apply-module-15.php
php scripts/apply-module-16.php
php scripts/apply-module-17.php
php scripts/apply-module-18.php
php scripts/apply-module-19.php
php scripts/apply-module-20.php
php scripts/apply-module-21.php
php scripts/apply-module-22.php
php scripts/apply-module-23.php
php scripts/apply-module-24.php
php scripts/apply-module-25.php
php scripts/apply-module-26.php
php scripts/apply-module-27.php
php scripts/apply-module-28.php
php scripts/apply-module-29.php
php scripts/apply-module-30.php
php scripts/apply-module-31.php
php scripts/apply-module-32.php
php scripts/apply-module-33.php
php scripts/apply-module-34.php
php scripts/apply-module-35.php
php scripts/apply-module-36.php
php scripts/apply-module-37.php
php scripts/apply-module-38.php
php scripts/apply-module-39.php
php scripts/apply-module-40.php
php scripts/apply-module-41.php
php scripts/apply-module-42.php
php scripts/apply-module-43.php
php scripts/apply-module-44.php
php scripts/apply-module-45.php
php scripts/verify-blueprint.php
npm run build
php artisan serve
```

---

## Assumptions (ambiguous blueprint items)

1. **Olance base retained** — `Job`/`Bid`/`Buyer`/`User` models kept; Blueprint terminology mapped in UI (“request/quote”).
2. **MVP monetisation** — Off by default after Module 12; Module 16 `apply-module-16.php` enables credits + subscriptions for go-live (use `--off` to disable).
3. **Accept quote** — Uses existing hire/escrow flow when enabled; no Stripe in MVP.
4. **Matching** — Service areas matched as text against `request_data` JSON; postcode radius deferred.
5. **Admin form builder** — Admin UI at `/admin/marketplace-forms`; category edit links request/quote forms.
6. **Contact hiding** — Emails, phone numbers, and links are masked in chat until a quote is accepted (`MessageSanitizer`).

---

## Remaining work (post Module 45)

Phase 4 (modules 31–45) completes marketplace admin React migration, postcode outcode matching, and gateway checkout polish.

Still intentionally Blade / deferred:

- General settings, frontend CMS builder, notification templates, language manager
- Automatic/manual gateway configuration screens
- Marketplace form field editor (complex form builder UI)
- Full native payment SDK React integrations (Stripe Elements, etc.)
- Postcode-radius geo API matching (outcode prefix used instead)

---

## How to verify Modules 14–45

Run from `Files/core`:

```bash
php scripts/verify-blueprint.php    # automated checks for all modules
php scripts/apply-module-14.php     # re-seed notification templates
php scripts/apply-module-15.php     # SEO locations + legal pages + sitemap
php scripts/apply-module-16.php     # enable monetisation (use --off to disable)
php scripts/apply-module-17.php     # notification inboxes + mobile polish check
php scripts/apply-module-18.php     # dashboard React pages check
php scripts/apply-module-19.php     # account React pages check
php scripts/apply-module-20.php     # auth/payment/tasks + final audit
npm run build
php artisan serve
```

### Manual browser checks

| Module | What to open | Expected |
|--------|--------------|----------|
| **14** | `/freelancer/notifications`, `/buyer/notifications` | React inbox lists; trigger shortlist/revision/chat to see entries |
| **15** | `/locations`, `/locations/london`, `/policy/privacy-policy`, `/sitemap.xml` | Location landing pages, legal policies, sitemap |
| **16** | `/freelancer/lead-credits`, `/pricing` | Credit packages / plans visible when monetisation enabled |
| **17** | Mobile viewport on dashboard + public home | Responsive layout; notification pages work |
| **18** | `/buyer/conversation`, `/freelancer/project/index`, `/freelancer/bid/index`, `/buyer/disputes` | React dashboard pages (not Blade bridge) |
| **19** | `/buyer/ticket`, `/buyer/withdraw`, `/buyer/kyc-form`, `/buyer/transactions` | React account pages for buyer + provider equivalents |
| **20** | `/buyer/password/reset`, `/buyer/authorization`, `/buyer/deposit` | React auth recovery, verify screens, deposit form |
| **21** | `/buyer/deposit/confirm` (with gateway) | Gateway checkout in React shell (Stripe, Paystack, etc.) |
| **22** | Freight job bid + `/buyer/job/post/bids/{id}` | Cost lines sum to total; compare shows breakdown |
| **23** | Provider browse with location in request | Stronger match when postcode/city aligns with service areas |
| **24** | `/admin/marketplace/dashboard`, `/admin/disputes` | React admin marketplace hub |
| **25** | `php scripts/verify-blueprint.php` | All modules 1–25 pass |
| **26** | `/admin/jobs/list`, `/admin/bids/index`, `/admin/jobs/details/{id}` | React job & quote admin |
| **27** | `/admin/provider-verifications`, `/admin/reviews` | React verification & review moderation |
| **28** | `/freelance-jobs` as logged-in provider | Location & service match score bars on cards |
| **29** | `/admin/freelancers/pending-approval` | React provider approval queue |
| **30** | `php scripts/verify-blueprint.php` | All modules 1–30 pass |
| **31** | `/admin/category/index`, subcategories, skills | React category management |
| **32** | `/admin/freelancers`, `/admin/freelancers/active` | React provider lists |
| **33** | `/admin/freelancers/detail/{id}` | React provider detail |
| **34** | `/admin/buyers`, `/admin/buyers/detail/{id}` | React customer admin |
| **35** | `/admin/monetisation/settings`, packages, plans | React monetisation admin |
| **36** | `/admin/deposit/all`, `/admin/withdraw/all` | React deposits & withdrawals |
| **37** | `/admin/project/all`, `/admin/project/details/{id}` | React project manager |
| **38** | `/admin/marketplace-forms` | React forms index (edit still Blade) |
| **39** | Provider with SW1A area + job with SW1A postcode | Postcode area match badge on job cards |
| **40** | `/buyer/deposit/confirm` | Gateway name shown in checkout shell |
| **41** | `/admin/ticket` | React support tickets |
| **42** | `/admin/trial-task/index` | React trial tasks (when enabled) |
| **43** | `/admin/report/transaction`, `/admin/withdraw/method` | React transactions & withdraw methods |
| **44** | `php scripts/apply-module-44.php` | Marketplace admin migration audit |
| **45** | `php scripts/verify-blueprint.php` | All modules 1–45 pass |

---

*Last updated: Module 45 Phase 4 closure (full marketplace admin React)*
