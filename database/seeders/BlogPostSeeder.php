<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BlogPost;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some featured blog posts
        BlogPost::factory()
            ->published()
            ->featured()
            ->count(5)
            ->create();

        // Create blog posts by category
        $categories = [
            'Entrepreneurship' => 8,
            'Business Strategy' => 6,
            'Startup Stories' => 10,
            'Leadership' => 5,
            'Marketing' => 7,
            'Technology' => 4,
            'Finance' => 3,
            'Personal Development' => 6,
            'Industry Insights' => 4,
            'Success Stories' => 7
        ];

        foreach ($categories as $category => $count) {
            BlogPost::factory()
                ->published()
                ->category($category)
                ->count($count)
                ->create();
        }

        // Create some unpublished blog posts (drafts)
        BlogPost::factory()
            ->unpublished()
            ->count(5)
            ->create();

        // Create some specific high-quality blog posts with custom content
        $featuredPosts = [
            [
                'title' => 'The Ultimate Guide to Building a Successful Startup',
                'category' => 'Entrepreneurship',
                'short_description' => 'Learn the essential steps, strategies, and mindset needed to transform your startup idea into a thriving business.',
                'description' => $this->getStartupGuideContent(),
                'meta_title' => 'Ultimate Startup Guide 2024 - Build Your Successful Business',
                'meta_description' => 'Complete guide to startup success. Learn proven strategies, avoid common pitfalls, and build a thriving business from scratch.',
                'is_published' => true,
                'published_at' => now()->subDays(7),
                'sort_order' => 1,
                'views_count' => 2500,
            ],
            [
                'title' => 'From Idea to IPO: The Journey of Modern Entrepreneurs',
                'category' => 'Success Stories',
                'short_description' => 'Explore the inspiring journeys of entrepreneurs who took their companies from initial concept to public offering.',
                'description' => $this->getIPOJourneyContent(),
                'meta_title' => 'Entrepreneur Success Stories - From Idea to IPO',
                'meta_description' => 'Inspiring stories of entrepreneurs who built billion-dollar companies. Learn from their challenges, strategies, and victories.',
                'is_published' => true,
                'published_at' => now()->subDays(14),
                'sort_order' => 2,
                'views_count' => 1800,
            ],
            [
                'title' => 'The Psychology of Leadership in High-Growth Companies',
                'category' => 'Leadership',
                'short_description' => 'Understanding the mental frameworks and psychological principles that drive effective leadership in rapidly scaling businesses.',
                'description' => $this->getLeadershipPsychologyContent(),
                'meta_title' => 'Leadership Psychology for High-Growth Companies',
                'meta_description' => 'Master the psychological aspects of leadership. Learn how top CEOs think, make decisions, and inspire teams in fast-growing companies.',
                'is_published' => true,
                'published_at' => now()->subDays(21),
                'sort_order' => 3,
                'views_count' => 1200,
            ]
        ];

        foreach ($featuredPosts as $postData) {
            BlogPost::create($postData);
        }
    }

    private function getStartupGuideContent(): string
    {
        return '<h2>Introduction</h2>
        <p>Building a successful startup is one of the most challenging yet rewarding endeavors an entrepreneur can undertake. This comprehensive guide will walk you through every essential step of the journey.</p>
        
        <h2>1. Validate Your Idea</h2>
        <p>Before investing time and money, ensure there\'s a real market need for your solution. Conduct thorough market research, interview potential customers, and test your assumptions.</p>
        
        <h2>2. Build Your MVP</h2>
        <p>Create a Minimum Viable Product that solves the core problem. Focus on essential features and get to market quickly to gather real user feedback.</p>
        
        <h2>3. Secure Funding</h2>
        <p>Explore various funding options including bootstrapping, angel investors, venture capital, and crowdfunding. Choose the path that aligns with your growth strategy.</p>
        
        <h2>4. Scale Your Team</h2>
        <p>Hire strategically and build a culture that attracts top talent. Your team is your most valuable asset in the journey to success.</p>
        
        <h2>Conclusion</h2>
        <p>Success in the startup world requires persistence, adaptability, and continuous learning. Stay focused on your vision while remaining flexible in your approach.</p>';
    }

    private function getIPOJourneyContent(): string
    {
        return '<h2>The Modern Entrepreneurial Landscape</h2>
        <p>Today\'s entrepreneurs face unique challenges and opportunities in the digital age. The path from idea to IPO has evolved significantly over the past decade.</p>
        
        <h2>Case Study: Tech Unicorns</h2>
        <p>Companies like Airbnb, Uber, and Stripe have redefined what it means to scale rapidly. Their journeys offer valuable lessons for aspiring entrepreneurs.</p>
        
        <h2>Key Success Factors</h2>
        <ul>
        <li>Market timing and opportunity recognition</li>
        <li>Strong founding team with complementary skills</li>
        <li>Scalable business model</li>
        <li>Effective capital allocation</li>
        <li>Strategic partnerships and network effects</li>
        </ul>
        
        <h2>Common Pitfalls to Avoid</h2>
        <p>Learn from the mistakes of others. Avoid premature scaling, neglecting company culture, and losing focus on core customers.</p>';
    }

    private function getLeadershipPsychologyContent(): string
    {
        return '<h2>The Mind of a Leader</h2>
        <p>Effective leadership in high-growth environments requires a unique psychological makeup. Understanding these mental frameworks can transform your leadership approach.</p>
        
        <h2>Decision-Making Under Uncertainty</h2>
        <p>Leaders must make critical decisions with incomplete information. Learn the cognitive tools and frameworks that help navigate uncertainty.</p>
        
        <h2>Building Psychological Safety</h2>
        <p>Create an environment where team members feel safe to take risks, make mistakes, and share innovative ideas without fear of retribution.</p>
        
        <h2>The Growth Mindset</h2>
        <p>Cultivate a mindset that embraces challenges, learns from failures, and sees effort as the path to mastery. This mindset is contagious and drives organizational success.</p>
        
        <h2>Emotional Intelligence in Leadership</h2>
        <p>Develop your ability to understand and manage emotions - both your own and those of your team members. EQ often matters more than IQ in leadership roles.</p>';
    }
}
