import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavGroup, type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, BookMarked, Wallet, Sun, Moon } from 'lucide-react';
import AppLogo from './app-logo';
import { useGlobalSecurity } from '@/contexts/GlobalSecurityContext';

const getMainNavGroups = (isAccessible: boolean): NavGroup[] => [
    {
        title: 'Main',
        items: [
            {
                title: 'Dashboard',
                href: dashboard(),
                icon: LayoutGrid,
            },
        ],
    },
    {
        title: 'Spiritual',
        items: [
            {
                title: 'Morning Adhkar',
                href: '/morning-adhkar',
                icon: Sun,
            },
            {
                title: 'Evening Adhkar',
                href: '/evening-adhkar',
                icon: Moon,
            },
            {
                title: 'Duas',
                href: '/duas',
                icon: BookMarked,
            },
        ],
    },
    ...(isAccessible ? [{
        title: 'Resources',
        items: [
            {
                title: 'Assets',
                href: '/assets',
                icon: Wallet,
            },
        ],
    }] : []),
];

const footerNavItems: NavItem[] = [
    /*{
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },*/
];

export function AppSidebar() {
    const { isAccessible } = useGlobalSecurity();
    const mainNavGroups = getMainNavGroups(isAccessible);

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain groups={mainNavGroups} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
