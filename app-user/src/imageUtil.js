
export const imageUrl = (thumbnail) => {
    const baseUrl = import.meta.env.VITE_APP_URL || 'https://course-server.sahet-dev.com';  // Update the baseUrl for images
    return `${baseUrl}/storage/${thumbnail}`;
};
