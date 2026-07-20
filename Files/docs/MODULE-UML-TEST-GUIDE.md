# QuoteMatch — Complete Blueprint Diagrams & Test Guide

Aligned to: **Complete Blueprint for Service and Product Comparison Website** (38 pages).

Interactive SVG diagrams (no Mermaid): open  
`Files/docs/module-diagrams.html` in any browser.

**Naming map (Blueprint → App)**

| Blueprint | App (Olance/QuoteMatch) |
|-----------|-------------------------|
| Customer | Buyer |
| Service Provider | Freelancer / User |
| Request | Job |
| Quote | Bid |
| Accept quote | Hire talent |

---

## §1–3 Overview

```
Customer → Request → Providers → Quotes → Compare → Accept → Review
                ↑
              Admin
```

Launch categories: **Builders & Home Improvement** · **Freight Forwarding & Logistics**

---

## §4 User types

- **Customer** — post request, compare quotes, message, accept, review  
- **Provider** — profile, docs, matching leads, quote, message, monetisation  
- **Admin** — approve providers, categories, disputes, payments, verify docs, CMS, analytics  
- **Sales Agent** — optional future role  

---

## §7 Main customer workflow (primary test path)

1. Visit website → Get Quotes  
2. Select category / subcategory  
3. Complete **structured form** (fields change by category)  
4. Create account  
5. Request goes **Live** to matching providers  
6. Providers submit quotes  
7. **Compare quotes** side-by-side  
8. Shortlist  
9. Message providers  
10. Accept one quote  
11. Job completed → leave review  

**Compare columns (§9):** price, provider, rating, verification, insurance, availability, location, response time, validity, inclusions/exclusions, payment terms  

---

## §8 Main provider workflow

1. Register (business details, categories, service areas)  
2. Complete profile (logo, portfolio, insurance, certificates, licences)  
3. Admin verifies (Not verified → … → Fully verified)  
4. View **matching** leads only  
5. Submit structured quote  
6. Communicate / revise  
7. Won or lost  

---

## §43 Builder journey (example)

Get quotes → Kitchen fitting → Postcode + photos + start date → Submit → 3 quotes → Compare → Message 2 → Accept 1 → Complete → Review  

## §44 Freight journey (example)

Compare freight → Sea freight China–UK → Origin/dest/dims → Invoice + packing list → Customs + tail lift → Quotes → Compare costs/transit → Accept → Review  

---

## §6 Core modules

| Module | Must include |
|--------|----------------|
| Public | Home, how it works, categories, pricing, auth, legal pages |
| Customer dash | Post, active/draft, quotes (received/shortlisted/accepted), messages, files, reviews, settings, notifications |
| Provider dash | Profile, verification, categories, areas, leads, quotes, won/lost, messages, reviews, credits/subscription, documents |
| Admin dash | Users, provider approval, categories, requests, quotes, reviews, payments, subscriptions, credits, disputes, CMS, reports |

---

## §24 Statuses (QA checkpoints)

**Request:** Draft → Pending review → Live → Paused / Closed / Quote accepted → Completed / Cancelled / Expired  

**Quote:** Submitted → Viewed → Shortlisted / Revised → Accepted / Rejected / Withdrawn / Expired  

**Provider:** Incomplete profile → Pending approval → Active / Suspended / Rejected  

---

## §13 Verification badges

Email · Phone · Company · Insurance · ID · Address · Licence · Fully verified  

Document statuses: Pending → Approved / Rejected / Expired  

---

## §14 Reviews

Only after quote accepted or job completed.  
Ratings: overall, price, communication, quality, timeliness, recommend + text/photos.  
Admin: hide abusive, mark verified, investigate disputes.  

---

## §15–16 Messaging & notifications

In-platform chat + files. Hide contact details until quote/lead/accept rules met.  
Notify: new quote, matching lead, shortlist, accept, messages, doc approval, disputes.  

---

## §17 Monetisation (MVP)

Customer side **free**. First version: lead credits **or** subscription (after beta).  
Also possible later: commission, featured listings, verification fee.  

---

## §25 Matching

Provider sees request if: active · covers category · covers location/route · approved · has credits (if required).  

Builders: postcode radius + trade. Freight: origin/dest + mode + customs.  

---

## §31 Disputes

Report: fake quote, no response, bad behaviour, wrong info, payment, review dispute, fraud.  
Admin: open case, notes, evidence, suspend, hide review, resolve.  

---

## §18 / §39 MVP acceptance criteria

- [ ] Customer registers and posts request  
- [ ] Provider registers and completes profile  
- [ ] Admin approves provider  
- [ ] Provider sees matching requests and submits quote  
- [ ] Customer compares, messages, accepts  
- [ ] Customer leaves review  
- [ ] Admin manages categories, users, requests, quotes, reviews  
- [ ] Mobile responsive · basic notifications · secure files  

MVP should **not** require initially: mobile app, complex escrow, AI, full accounting, video calls, advanced disputes, full commission payments (§18).  

---

## §38 Milestones

| # | Focus |
|---|--------|
| M1 | Planning, roles, categories, wireframes, schema |
| M2 | Auth, 3 dashboards, post request, submit quote |
| M3 | Messaging, comparison, shortlist/accept/reject, notifications, uploads |
| M4 | Profile, docs, verification, reviews, reporting |
| M5 | QA, security, mobile, UAT, beta |
| M6 | Stripe, subscriptions, lead credits, featured, billing |

---

## App smoke URLs

```
/
/post-job
/buyer/login  /buyer/dashboard  /buyer/job/post/job-details  /buyer/job/post/bids
/freelancer/login  /freelancer/dashboard  /freelancer/bid/list  /freelancer/verification
/admin  /admin/jobs/pending  /admin/provider-verifications  /admin/disputes
/admin/monetisation/settings
```

Open diagrams: `Files/docs/module-diagrams.html`
